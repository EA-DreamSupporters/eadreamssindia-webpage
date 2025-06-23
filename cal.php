<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script>
$(document).ready(function () {

$('.variant').change(function() {
  var size = $(".variant").get().map(function(el) {
    return el.value
  }).join(" / "); //get value of slected options and then join
  $("select#data >  option:contains(" + size + ")").prop('selected', true); //set selected value
});
  });
</script>
<form action="#" method="post">JSLint

  <select class="variant">
    <option value="S">S</option>
    <option value="M">M</option>
    <option value="L">L</option>
  </select>

  <br>
  <select id="data">
    <option value="Grey">S</option>
    <option value="Red">M</option>
    <option value="Green">L</option>

  </select>

</form>