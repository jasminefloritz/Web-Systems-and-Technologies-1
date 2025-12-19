<?php

?>

<div class="live-search-container" style="display:flex;gap:.5rem;align-items:center;margin-top:0.75rem;">
  <input type="search" class="live-search" placeholder="Search..." aria-label="Search table" data-target=".admin-table tbody tr">
  <button type="button" class="btn live-search-clear">Clear</button>
</div>
<?php


$jqueryPath = __DIR__ . '/../js/jquery-3.7.1.js';
if (file_exists($jqueryPath)) {
  echo "<script>if(typeof jQuery === 'undefined'){var s=document.createElement('script');s.src='../js/jquery-3.7.1.js';document.head.appendChild(s);}</script>\n";
}
?>
<script src="../js/live_search.js"></script>