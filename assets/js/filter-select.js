function filterSelect(event) {
  event.stopPropagation();
  const dropdown = event.currentTarget.closest(".dropdown");
  const wasActive = dropdown.classList.contains("active");

  // Close all dropdowns
  document
    .querySelectorAll(".dropdown")
    .forEach((d) => d.classList.remove("active"));

  // Toggle the clicked dropdown
  if (!wasActive) {
    dropdown.classList.add("active");
  }
}

// Handle dropdown item selection
document.querySelectorAll(".dropdown-item").forEach((item) => {
  item.addEventListener("click", (e) => {
    const dropdown = e.currentTarget.closest(".dropdown");
    const selectedValue = e.currentTarget.textContent.trim();
    dropdown.querySelector(".selected-value").textContent = selectedValue;

    const filterValue = selectedValue.toLowerCase().replace(/\s+/g, "-");
    dropdown.classList.remove("active");

    const params = new URLSearchParams(window.location.search);

    if (dropdown.classList.contains("filter-date")) {
      params.set("date", filterValue);
    }

    if (dropdown.classList.contains("filter-sort-by")) {
      params.set("sort-by", filterValue);
    }

    window.location.href = `${window.location.pathname}?${params.toString()}`;
  });
});

// Close dropdown when clicking outside
document.addEventListener("click", (e) => {
  if (!e.target.closest(".dropdown")) {
    document
      .querySelectorAll(".dropdown")
      .forEach((d) => d.classList.remove("active"));
  }
});
