<?php
session_start();
if(isset($_SESSION["isAdmin"])){
                    if($_SESSION["isAdmin"] == 1){
                        echo "true";                        
                    }
				} else {
					echo "false";    
}
?>