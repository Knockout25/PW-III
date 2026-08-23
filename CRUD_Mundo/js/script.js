document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".delete-link").forEach((a) =>
    a.addEventListener("click", (e) => {
      if (!confirm("Tem certeza que deseja excluir este registro?"))
        e.preventDefault();
    }),
  );
  document.querySelectorAll("form").forEach((f) =>
    f.addEventListener("submit", (e) => {
      if (!f.checkValidity()) {
        e.preventDefault();
        f.reportValidity();
      }
    }),
  );
});
