import gsap from "gsap";
let _ = require("lodash");

Alpine.store("animate", {
    enter: (target, fn) => {
        if (!target) return;
        if (target instanceof Element) {
            gsap.fromTo(
                target.closest(".dialog"),
                { autoAlpha: 0 },
                { autoAlpha: 1, duration: .3, right: 0 }
            );
        }
        gsap.fromTo(
            target,
            {autoAlpha: 0 },
            {autoAlpha: 1, duration: .3,right: 0 }
        ).eventCallback("onComplete", () => {
            fn ? fn(target) : null;
        });
    },
    leave: (target, fn) => {
        if (!target) return;
        gsap.fromTo(
            target,
            {autoAlpha: 1 },
            {autoAlpha: 0, duration: .4, right: '-100%' }
        ).eventCallback("onComplete", () => {
            fn ? fn(target) : null;
        });
        if (target instanceof Element) {
            gsap.fromTo(
                target.closest(".dialog"),
                { autoAlpha: 1 },
                { autoAlpha: 0, duration: .4, right: '-100%' }
            );
        }
    },
});

Alpine.store("libs", {
    getLastIndex: function (target) {
        return Math.max(
            ...Array.from(target ?? document.querySelectorAll("*"), (el) =>
                parseFloat(window.getComputedStyle(el).zIndex)
            ).filter((zIndex) => !Number.isNaN(zIndex)),
            0
        );
    },
    playAnimateOnLoad: function (target) {
        Alpine.store("animate").enter(target, (res) => {
            target?.removeAttribute("style");
        });
    },
});

Alpine.magic("json", () => (str) => {
    return JSON.parse(str);
});

Alpine.magic("isEmpty", () => (value) => {
    return _.isEmpty(value);
});

Alpine.magic("mask", () => (str, mask) => {
    let newStr = "";
    let strIndex = 0;
    for (let i = 0; i < mask?.length; i++) {
        if (mask[i] === "#") {
            newStr += str[strIndex] ?? "";
            strIndex++;
        } else {
            newStr += mask[i];
        }
    }
    return newStr;
});
