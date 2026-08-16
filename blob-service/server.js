import http from "node:http";
import { put, del } from "@vercel/blob";

const MAX_BODY = 4 * 1024 * 1024;

function json(res, status, payload) {
  const body = JSON.stringify(payload);
  res.writeHead(status, {
    "content-type": "application/json; charset=utf-8",
    "content-length": Buffer.byteLength(body),
  });
  res.end(body);
}

function authorized(req) {
  const expected = process.env.UPLOAD_API_SECRET;
  const supplied = req.headers["x-upload-secret"];
  return Boolean(expected && supplied && supplied === expected);
}

function readBody(req, maxBytes = MAX_BODY) {
  return new Promise((resolve, reject) => {
    const chunks = [];
    let total = 0;

    req.on("data", (chunk) => {
      total += chunk.length;
      if (total > maxBytes) {
        reject(new Error("Upload is too large."));
        req.destroy();
        return;
      }
      chunks.push(chunk);
    });

    req.on("end", () => resolve(Buffer.concat(chunks)));
    req.on("error", reject);
  });
}

function cleanPart(value, fallback) {
  const cleaned = String(value || "")
    .replace(/[^A-Za-z0-9._-]+/g, "-")
    .replace(/^-+|-+$/g, "");
  return cleaned || fallback;
}

const server = http.createServer(async (req, res) => {
  try {
    const url = new URL(req.url, "http://localhost");

    if (url.pathname === "/health") {
      return json(res, 200, { ok: true });
    }

    if (!authorized(req)) {
      return json(res, 401, { error: "Unauthorized" });
    }

    if (req.method === "POST" && url.pathname === "/blob-upload") {
      const contentType = String(req.headers["content-type"] || "");
      if (contentType !== "image/webp") {
        return json(res, 400, { error: "Only WEBP uploads are accepted." });
      }

      const body = await readBody(req);
      if (!body.length) {
        return json(res, 400, { error: "Empty upload." });
      }

      const folder = cleanPart(url.searchParams.get("folder"), "uploads");
      let filename = cleanPart(url.searchParams.get("filename"), `image-${Date.now()}.webp`);

      if (!filename.toLowerCase().endsWith(".webp")) {
        filename += ".webp";
      }

      const blob = await put(`${folder}/${filename}`, body, {
        access: "public",
        contentType: "image/webp",
        addRandomSuffix: true,
      });

      return json(res, 200, {
        url: blob.url,
        pathname: blob.pathname,
      });
    }

    if (req.method === "POST" && url.pathname === "/blob-delete") {
      const body = await readBody(req, 64 * 1024);
      const parsed = JSON.parse(body.toString("utf8") || "{}");

      if (!parsed.url || typeof parsed.url !== "string") {
        return json(res, 400, { error: "Missing Blob URL." });
      }

      await del(parsed.url);
      return json(res, 200, { ok: true });
    }

    return json(res, 404, { error: "Not found" });
  } catch (error) {
    console.error(error);
    return json(res, 500, {
      error: error?.message || "Blob service failed.",
    });
  }
});

const port = Number(process.env.PORT || 3000);
server.listen(port, "0.0.0.0", () => {
  console.log(`Spectrum Blob service listening on ${port}`);
});
