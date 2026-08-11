
function revealToSpan() {
  document.querySelectorAll(".reveal").forEach(function(elem) {
    var parent = document.createElement("span");
    var child = document.createElement("span");

    parent.classList.add("parent");
    child.classList.add("child");

    child.innerHTML = elem.innerHTML;
    parent.appendChild(child);

    elem.innerHTML = "";
    elem.appendChild(parent);
  });
}


function valueSetters(){
gsap.set("#home .parent  .child", { y:"100%" })
gsap.set("#home .row img", { opacity: 0 });
  gsap.set("#imagery", { opacity: 0 }); 
} 


function loaderAnimation() {
  document.body.classList.add('noscroll');

  var tl = gsap.timeline();

  tl.from("#loader .child span", {
    x: 100,
    duration: 2,
    delay: 0,
    stagger: 0.2,
    ease: Power3.easeInOut
  })
  .to("#loader .parent .child", {
    y: "-110%",
    duration: 1,
    ease: Circ.easeInOut
  })
  .to("#loader", {
    height: 0,
    duration: 1,
    ease: Circ.easeInOut
  })
  .set("#loader", {
    display: "none"
  })
  .add(() => {
    document.body.classList.remove('noscroll'); 
  })
  .add(animatehomepage);
}


function animatehomepage() {
  var tl = gsap.timeline();

  tl.to("#home .parent .child", {
    y: 0,
    stagger: .1,
    duration: 1,
    ease: Expo.easeInOut
  });

  tl.to("#home .row img", {
    opacity: 1,
    delay: -0.5,
    ease: Expo.easeInOut
  });

  tl.to("#imagery", {
    opacity: 1,
    duration: 1.5,
    ease: "power2.out"
  });
}
revealToSpan();
valueSetters();
loaderAnimation();



  gsap.to("#imgrig .imgcntr:nth-child(1)", {
    scrollTrigger: {
      trigger: "#imagery",
      start: "top center",
      end: "bottom top",
      scrub: true,
      
    },
    rotate: -10,
    x: -50,
    y: -30,
    ease: "power2.out",
  });

  gsap.to("#imgrig .imgcntr:nth-child(2)", {
    scrollTrigger: {
      trigger: "#imagery",
      start: "top center",
      end: "bottom top",
      scrub: true,
    },
    rotate: 10,
    x: 50,
    y: 30,
    ease: "power2.out",
  });


 


gsap.timeline({
  scrollTrigger:{
    trigger:"#vision",
    start:"50% 50%",
    end:"150% 50%",
    scrub:2,
    pin:true
  }
})
.to("#center",{ height: "100vh" },'a')
.to("#top",{ top: "-50%" },'a')
.to("#bottom",{ bottom: "-50%" },'a')
.to("#top-h1",{ yPercent: -50 },'a')       // moves top half up
.to("#bottom-h1",{ yPercent: 50 },'a')     // moves bottom half down
.to("#center-h1",{ yPercent: -30 },'a')

.to(".content", {
  marginTop: "0%",   // moves content from 100% → 0%
  ease: "none"       // ensures smooth linear scroll
});


gsap.to(".line", {
  width: "100%",
  ease: "none",
  scrollTrigger: {
    trigger: ".line",
    start: "top 80%",
    end: "top 30%",
    scrub: true
  }
});
