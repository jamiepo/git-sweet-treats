<?
// The factory method
class Factory {
  function makeDriver($type, $path) {
  	include_once $path.'/class.db.php';
    if (include_once $path.'/class.db' . $type . '.php') {
      $classname = 'Driver_' . $type;
      return new $classname;
    } else {
      die ('Driver not found');
    }
  }
}
?>