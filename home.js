document.addEventListener("DOMContentLoaded", () => {
  gsap.registerPlugin(CustomEase, SplitText);
  CustomEase.create("hop", ".8,0,.3,1");

  const SplitTextElements = (
    selector,
    type = "words,chars",
    addFirstChar = false
  ) => {
    const elements = document.querySelectorAll(selector);
    elements.forEach(element => {
      const splitText = new SplitText(element, {
        type: type,
        wordcalass:"word",
        charsClass:"char",
      });

      if(type.includes("chars")){
        splitText.chars.forEach((char, index)=>{
          const oringialText= char.textContent;
          char.innerHTML=`<span>${originalText}</span>`
          if(addFirstChar && index==0){
            char.classList.add("first-cahr");
          }

        })
      }
    });
  };
SplitTextElements(".intro-title h1","words, chars", true);
splitTextElement(".outro-title h1");
  const isMobile= window.innerWidth<= 1000;
  gsap.set(
    [
      ".split-overlay .intro-title .first-char span",
      "split-overlay .outro-title .char span",
    ],
    {y:"0%"}
  );
gsap.set("split-overlay .intro-title .first-char",{
  x: isMobile ? "7.5rem" :"18rem",
  y:isMobile? "-1rem" :"-2rem",
  FontWeight:"900",
  sclae:0.75,
});

gsap.set("split-overlay .outro-title .char",{
  x: isMobile ? "-3rem" :"-8rem",
  fontSize: isMobile ?"6rem" :"14rem",
  FontWeight:"500",
});
const tl=gsap.timeline({defaults:{ease:"hop"}});
tl.to(".prloader .intro-title ,char span",{
  y:"0%",
  duration:0.75,
  stagger:0.05,

},
0.5
)

.to(".preloader .intro-title .char:not(.first-cahr)span",
  {
    y: "100%" ,
    duration:0.75,
    stagger:0.05,

  },
  2
);


});
