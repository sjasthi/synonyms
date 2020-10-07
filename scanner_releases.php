<?php

  $nav_selected = "SCANNER"; 
  $left_buttons = "YES"; 
  $left_selected = "RELEASES"; 

  include("./nav.php");
  global $db;

  ?>


<div class="right-content">
    <div class="container">

      <h3 style = "color: #01B0F1;">Scanner -> System Releases</h3>

        <h3><img src="images/releases.png" style="max-height: 35px;" />System Releases</h3>

        <table id="info" cellpadding="0" cellspacing="0" border="0"
            class="datatable table table-striped table-bordered datatable-style table-hover"
            width="100%" style="width: 100px;">
              <thead>
                <tr id="table-first-row">
                        <th>id</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Open Date</th>
                        <th>Dependency Date</th>
                        <th>Content Date</th>
                        <th>RTM Date(s)</th>
                        <th>Manager</th>
                        <th>Author</th>
                        <th>BOM ID</th>
                </tr>
              </thead>

              <tfoot>
                <tr>
                        <th>Name</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Open Date</th>
                        <th>Dependency Date</th>
                        <th>Content Date</th>
                        <th>RTM Date(s)</th>
                        <th>Manager</th>
                        <th>Author</th>
                        <th>BOM ID</th>
                </tr>
              </tfoot>

              <tbody>

              <?php

$sql = "SELECT * from releases ORDER BY rtm_date ASC;";
$result = $db->query($sql);

                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>
                                <td>'.$row["id"].'</td>
                                <td>'.$row["name"].' </span> </td>
                                <td>'.$row["type"].'</td>
                                <td>'.$row["status"].'</td>
                                <td>'.$row["open_date"].' </span> </td>
                                <td>'.$row["dependency_date"].'</td>
                                <td>'.$row["freeze_date"].'</td>
                                <td>'.$row["rtm_date"].' </span> </td>
                                <td>'.$row["manager"].' </span> </td>
                                <td>'.$row["author"].' </span> </td>
                                <td>'.$row["app_id"].' </span> </td>
                            </tr>';
                    }//end while
                }//end if
                else {
                    echo "0 results";
                }//end else

                 $result->close();
                ?>

              </tbody>
        </table>


        <script type="text/javascript" language="javascript">
    $(document).ready( function () {
        
        $('#info').DataTable( {
            dom: 'lfrtBip',
            buttons: [
                'copy', 'excel', 'csv', 'pdf'
            ] }
        );

        $('#info thead tr').clone(true).appendTo( '#info thead' );
        $('#info thead tr:eq(1) th').each( function (i) {
            var title = $(this).text();
            $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    
            $( 'input', this ).on( 'keyup change', function () {
                if ( table.column(i).search() !== this.value ) {
                    table
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            } );
        } );
    
        var table = $('#info').DataTable( {
            orderCellsTop: true,
            fixedHeader: true,
            retrieve: true
        } );
        
    } );

</script>

        

 <style>
   tfoot {
     display: table-header-group;
   }
 </style>

  <?php include("./footer.php"); ?>
