// ========================= CATEGORY SIDEBAR TOGGLE =========================

document.addEventListener("DOMContentLoaded", function () {
  const categoryIcons = document.querySelectorAll(".cartegory-title span");

  categoryIcons.forEach(function (icon) {
    icon.addEventListener("click", function (event) {
      event.preventDefault();
      event.stopPropagation();

      const parentLi = this.closest(".cartegory-left-li");

      if (parentLi) {
        parentLi.classList.toggle("active");
      }
    });
  });

  // ========================= FILTER DROPDOWN =========================

  const filterWrapper = document.querySelector(".filter-wrapper");
  const filterToggle = document.getElementById("filterToggle");

  if (filterWrapper && filterToggle) {
    filterToggle.addEventListener("click", function (event) {
      event.preventDefault();
      event.stopPropagation();

      filterWrapper.classList.toggle("active");
    });

    document.addEventListener("click", function (event) {
      if (!filterWrapper.contains(event.target)) {
        filterWrapper.classList.remove("active");
      }
    });

    const filterDropdown = document.querySelector(".filter-dropdown");

    if (filterDropdown) {
      filterDropdown.addEventListener("click", function (event) {
        event.stopPropagation();
      });
    }
  }
});
