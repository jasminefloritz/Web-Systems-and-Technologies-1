// Simple reusable live table search/filter
(function () {
  "use strict";

  function debounce(fn, wait) {
    let t;
    return function () {
      const args = arguments;
      clearTimeout(t);
      t = setTimeout(() => fn.apply(null, args), wait);
    };
  }

  function createNoResultsRow(tbody, colCount, text) {
    const tr = document.createElement("tr");
    tr.className = "no-results-row";
    const td = document.createElement("td");
    td.colSpan = colCount;
    td.style.textAlign = "center";
    td.style.padding = "1rem";
    td.textContent = text;
    tr.appendChild(td);
    return tr;
  }

  function initInput(input) {
    const targetSelector = input.dataset.target || ".admin-table tbody tr";
    const noResultsText = input.dataset.noResults || "No results found.";
    const caseSensitive = input.dataset.caseSensitive === "true";
    const tbodyRowSelector = targetSelector;

    const container = input.closest(".live-search-container") || document;
    const table = container.querySelector(".admin-table");
    const tbody = table ? table.querySelector("tbody") : null;
    const colCount = table
      ? table.querySelectorAll("thead th").length ||
        table.querySelectorAll("tr:first-child td").length ||
        1
      : 1;
    let noResultsRow = null;

    function filter() {
      const q = input.value.trim();
      const needle = caseSensitive ? q : q.toLowerCase();
      const rows = tbody
        ? Array.from(tbody.querySelectorAll("tr"))
        : Array.from(document.querySelectorAll(tbodyRowSelector));
      let matches = 0;

      rows.forEach((row) => {
        // skip no-results row if present
        if (row.classList && row.classList.contains("no-results-row")) return;
        const text = caseSensitive
          ? row.textContent
          : row.textContent.toLowerCase();
        const matched = needle === "" || text.indexOf(needle) !== -1;
        row.style.display = matched ? "" : "none";
        if (matched) matches++;
      });

      if (matches === 0) {
        if (!noResultsRow && tbody) {
          noResultsRow = createNoResultsRow(tbody, colCount, noResultsText);
          tbody.appendChild(noResultsRow);
        }
      } else {
        if (noResultsRow && noResultsRow.parentNode) {
          noResultsRow.parentNode.removeChild(noResultsRow);
          noResultsRow = null;
        }
      }
    }

    const debounced = debounce(filter, 220);

    input.addEventListener("input", debounced);

    // clear button if present in the container
    const clear = container.querySelector(".live-search-clear");
    if (clear) {
      clear.addEventListener("click", function () {
        input.value = "";
        filter();
        input.focus();
      });
    }

    // kick off to ensure correct state
    filter();
  }

  document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll(".live-search");
    inputs.forEach(initInput);
  });
})();
