document.addEventListener("DOMContentLoaded", function () {
  const searchContainer = document.querySelector(".search-container");
  const searchInput = searchContainer.querySelector("#chapter-search");
  const resultsDiv = searchContainer.querySelector("#search-results");
  const loadingIndicator = searchContainer.querySelector("#search-loading");
  let debounceTimer; // Timer for debounce

  function fetchResults(query) {
    const queryParams = new URLSearchParams();
    queryParams.set("action", "search_chapters");

    if (query) {
      queryParams.set("query", query);
    }
    const url = `/wp-admin/admin-ajax.php?${queryParams.toString()}`;

    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        // Hide loading indicator
        loadingIndicator.classList.add("hidden");
        searchContainer.classList.add("active");

        if (data.length > 0) {
          resultsDiv.innerHTML = data
            .map((chapter) => {
              return `
                <div class="search-result-item">
                    <a href="${chapter.url}" class="text-[14px] font-semibold text-white">
                        ${chapter.title}
                    </a>
                </div>
              `;
            })
            .join("");
        } else {
          resultsDiv.innerHTML =
            "<div class='search-result-item text-[14px] font-semibold text-white'>No results found</div>";
        }
      })
      .catch((error) => {
        // Hide loading indicator and show error
        loadingIndicator.classList.add("hidden");
        console.error("Error fetching results:", error);
        resultsDiv.innerHTML =
          "<div class='search-result-item text-[14px] font-semibold text-white'>Error loading results</div>";
      });
  }

  searchInput.addEventListener("keyup", function () {
    let query = searchInput.value.trim();

    if (query.length < 3) {
      searchContainer.classList.remove("active");
      resultsDiv.innerHTML = "";
      return;
    }

    // Show loading indicator
    loadingIndicator.classList.remove("hidden");

    // Clear the previous timer and set a new one
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
      fetchResults(query);
    }, 500); // 500ms delay
  });

  // Close search results when clicking outside
  document.addEventListener("click", (e) => {
    if (!e.target.closest(".search-container")) {
      searchContainer.classList.remove("active");
      loadingIndicator.classList.add("hidden");
    }
  });
});
