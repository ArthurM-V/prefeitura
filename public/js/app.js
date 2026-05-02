"use strict";

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".alert[data-auto-close]").forEach((el) => {
    setTimeout(() => el.remove(), 6000);
  });
});
