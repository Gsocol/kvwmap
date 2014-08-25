<?php
# Script wird zum Ende von index.php abgearbeitet

# Schlie�en der offenen Datenbankverbindungen
if (isset($user)) {
  if ($user->database->dbConn>0) { $user->database->close(); }
}  
if ($GUI->database->dbConn>0) { $GUI->database->close(); }
if ($GUI->pgdatabase->dbConn>0) { $GUI->pgdatabase->close(); } 
if (DEBUG_LEVEL>0) { $debug->close(); }
# Schlie�en des Postgres Logfiles
if (LOG_LEVEL>0){
  $log_postgres->close();
}
?>
