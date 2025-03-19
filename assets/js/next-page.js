function nextPage() {
  const params = new URLSearchParams(window.location.search);
  const currentPage = parseInt(params.get("pg") || "1");
  params.set("pg", currentPage + 1);
  window.location.href = `${window.location.pathname}?${params.toString()}`;
}
