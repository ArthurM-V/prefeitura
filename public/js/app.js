"use strict";

window.adminReloadWithFlash = (message) => {
  sessionStorage.setItem(
    "adminFlash",
    message || "Operação realizada com sucesso.",
  );
  location.reload();
};

window.adminSubmitForm = async (event, form, url) => {
  event.preventDefault();
  const resp = await fetch(url, {
    method: "POST",
    body: new FormData(form),
  });
  const data = await resp.json();

  if (data.success) {
    adminReloadWithFlash(data.message);
    return;
  }

  alert(data.message || "Erro ao salvar.");
};

window.adminDeleteItem = async (id, url, confirmMessage) => {
  if (!confirm(confirmMessage)) return;

  const body = new URLSearchParams({ id });
  const resp = await fetch(url, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body,
  });
  const data = await resp.json();

  if (data.success) {
    adminReloadWithFlash(data.message);
    return;
  }

  alert(data.message || "Erro ao excluir.");
};

document.addEventListener("DOMContentLoaded", () => {
  const adminMain = document.querySelector(".admin-main");
  const adminFlash = sessionStorage.getItem("adminFlash");

  if (adminMain && adminFlash) {
    sessionStorage.removeItem("adminFlash");
    const container = adminMain.querySelector(".container");

    if (container) {
      const alert = document.createElement("div");
      alert.className = "alert alert-success";
      alert.dataset.autoClose = "true";
      alert.textContent = adminFlash;
      container.insertBefore(alert, container.firstElementChild);
    }
  }

  document.querySelectorAll(".alert[data-auto-close]").forEach((el) => {
    setTimeout(() => el.remove(), 6000);
  });
});
