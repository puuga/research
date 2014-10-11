<?php //research.php ?>
<?php include 'login_control.php'; ?>
<?php include 'db_connect.php'; ?>
<?php include "class_import.php"; ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Science Research</title>

    <?php include 'head_tag.php'; ?>

    <script>

      $( document ).ready(function() {
        if(getParameterByName("search_keyword") != "") {
          $("#advance_search").hide();
        } else if(getParameterByName("advance_search") != "") {
          $("#normal_search").hide();
        } else {
          $("#advance_search").hide();
        }

      });

      // globol var
      var researchs = [];
      var idToDelete;

      function setDataForDetail(id) {
        console.log("researchs.length:"+researchs.length);
        console.log("id:"+id);

        var research;
        for (var i=0; i<researchs.length; i++) {
          if (researchs[i].id == id) {
            research = researchs[i];
            break;
          }
        }

        $("#researchTitleDetail").html(research.title);
        $("#researchTitleDetailForStudentGraduation").html(research.is_student_grad);
        $("#researchTitleDetailAuthor").html(research.author_name_th+"<br/>"+research.author_name_en);
        $("#researchTitleDetailCorresponding").html(research.corresponding);
        $("#researchTitleDetailReference").html(research.reference);

        if (research.research_type == "journal") {
          $("#researchTitleDetailForJournal").show();
          $("#researchTitleDetailForProceeding").hide();

          $("#researchTitleDetailJournalTitle").html(research.journal_name);


          $("#researchTitleDetailJournalNationalInternational").html(research.journal_type);
          var message = "";
          if(research.journal_type == "national") {
            message += research.journal_national_group + " ";
          } else {
            console.log(research.is_journal_international_ISI);
            if(research.is_journal_international_ISI == "1") {
              message += "ISI ";
            }
            if(research.is_journal_international_SCOPUS == "1") {
              message += "SCOPUS ";
            }
            if(research.is_journal_international_SJR == "1") {
              message += "SJR " + research.journal_international_group_sjr;
            }
          }
          $("#researchTitleDetailJournalNationalInternationalDetail").html(message);

          $("#researchTitleDetailJournalPublishedInpress").html(research.journal_type_progress);
          message = "";
          if(research.journal_type_progress == "public") {
            message += "Vol. " + research.journal_vol + "<br/>";
            message += "Issue No. " + research.journal_issue + "<br/>";
            message += "Number. " + research.journal_number + "<br/>";
            message += "From Page. " + research.journal_page_start + "<br/>";
            message += "To Page. " + research.journal_page_end + "<br/>";
            message += "DOI no. " + research.journal_doi_no + "<br/>";
            message += "Accepted date. " + research.journal_accepted_date + "<br/>";
            message += "Published month. " + research.journal_published_month + "<br/>";
            message += "Published year. " + research.journal_published_year + "<br/>";

          }
          $("#researchTitleDetailJournalPublishedInpressDetail").html(message);

        } else if (research.research_type == "conference") {
          $("#researchTitleDetailForProceeding").show();
          $("#researchTitleDetailForJournal").hide();

          $("#researchTitleDetailConferenceName").html(research.conference_name);
          $("#researchTitleDetailVenue").html(research.conference_address);

          $("#researchTitleDetailStartDate").html(research.conference_start_date);
          $("#researchTitleDetailEndDate").html(research.conference_end_date);
          $("#researchTitleDetailPageFrom").html(research.conference_page_start);
          $("#researchTitleDetailPageTo").html(research.conference_page_end);
          $("#researchTitleDetailNationalInternational").html(research.conference_location_type);
          $("#researchTitleDetailOralPoster").html(research.conference_type);

        }

        $("#researchTitleDetailAttFile").attr("href", research.att_file);
      }

      function showAdvanceSearch() {
        $("#normal_search").slideToggle();
        $("#advance_search").slideToggle();
      }

      function hideAdvanceSearch() {
        $("#normal_search").slideToggle();
        $("#advance_search").slideToggle();
      }

      function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
      }

    </script>

    <?php
      function getVal($valName) {
        $search_string = str_replace("+"," ",$_GET[$valName]);
        return !empty($_GET[$valName]) ? "value='$search_string'" : "" ;
      }
    ?>

  </head>

  <body>
    <?php include 'navbar.php'; ?>

    <div class="container">
      <!--
      <div class="row">

        <div class="col-md-4">
          <div class="list-group">
            <a href="research_view.php" class="list-group-item">
              <span class="glyphicon glyphicon-list-alt"></span> Paper Management System
            </a>
            <a href="#" class="list-group-item">
              <span class="glyphicon glyphicon-tag"></span> Patent Management System
            </a>
            <a href="staff_view.php" class="list-group-item">
              <span class="glyphicon glyphicon-user"></span> Staff Management System
            </a>
          </div>
        </div>

      </div>
      -->
      <div>

        <div id="normal_search">
          <form class="form-horizontal" role="form" action="main_menu.php">

            <div class="form-group">
              <label for="search_keyword" class="col-sm-2 control-label">Keyword:</label>
              <div class="col-sm-7">
                <input list="unified_datas"
                  id="search_keyword"
                  name="search_keyword"
                  class="form-control"
                  <?php echo getVal("search_keyword"); ?>
                  >
                <datalist id="unified_datas">
                  <?php
                    // set sql
                    $sql = "SELECT name_th FROM research.unified_data_view ";
                    $result_for_json = array();
                    $result = mysqli_query($con, $sql);
                    if (!$result) {
                      die('Error: ' . mysqli_error($con));
                    } else {
                      while($row = mysqli_fetch_array($result)) {
                        echo "<option value='".$row["name_th"]."'>";
                      }
                    }
                  ?>
                </datalist>
              </div>

              <div class="col-sm-3">
                <button type="submit" class="btn btn-primary">
                  <span class="glyphicon glyphicon-search"></span> Search</button>

                <a href="javascript:showAdvanceSearch()" class="btn btn-success">
                  <span class="glyphicon glyphicon-search"></span> Advance Search</a>

              </div>
            </div>

          </form>

        </div>

        <div id="advance_search">
          <div class="text-right">
            <p>
              <a href="javascript:hideAdvanceSearch()" class="btn btn-danger">
                <span class="glyphicon glyphicon-remove"></span>
              </a>
            </p>
          </div>

          <form class="form-horizontal" role="form" action="main_menu.php">
            <input type="hidden" name="advance_search" value="true"/>

            <div class="form-group">
              <label for="paper_title" class="col-sm-2 control-label">Paper Title:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control"
                  id="paper_title" name="paper_title"
                  <?php echo getVal("paper_title"); ?>
                  >
              </div>
            </div>

            <div class="form-group">
              <label for="paper_author" class="col-sm-2 control-label">Author:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control"
                  id="paper_author" name="paper_author"
                  <?php echo getVal("paper_author"); ?>
                  >
              </div>
            </div>

            <div class="form-group">
              <div class="col-sm-2 control-label">
                Paper type:
              </div>

              <div class="col-sm-2">
                <div class="radio">
                  <label>
                    <input type="checkbox" name="options_journal" id="options_journal" value="true"
                      <?php
                        if( empty($_GET["advance_search"]) ) {
                          echo "checked";
                        } else {
                          if( !empty($_GET["options_journal"]) ) {
                            echo "checked";
                          }
                        }
                      ?> >
                    Journal
                  </label>
                </div>
              </div>

              <div class="col-sm-2">
                <div class="radio">
                  <label>
                    <input type="checkbox" name="options_conference" id="options_conference" value="true"
                    <?php
                      if( empty($_GET["advance_search"]) ) {
                        echo "checked";
                      } else {
                        if( !empty($_GET["options_conference"]) ) {
                          echo "checked";
                        }
                      }
                    ?> >
                    Conference
                  </label>
                </div>
              </div>

            </div>

            <div class="form-group">

              <div class="col-sm-offset-2 col-sm-2">
                <div class="radio">
                  <label>
                    <input type="checkbox" name="options_international" id="options_international" value="true"
                    <?php
                      if( empty($_GET["advance_search"]) ) {
                        echo "checked";
                      } else {
                        if( !empty($_GET["options_international"]) ) {
                          echo "checked";
                        }
                      }
                    ?> >
                    International
                  </label>
                </div>
              </div>

              <div class="col-sm-2">
                <div class="radio">
                  <label>
                    <input type="checkbox" name="options_national" id="options_national" value="true"
                    <?php
                      if( empty($_GET["advance_search"]) ) {
                        echo "checked";
                      } else {
                        if( !empty($_GET["options_national"]) ) {
                          echo "checked";
                        }
                      }
                    ?> >
                    National
                  </label>
                </div>
              </div>

            </div>

            <div class="form-group">
              <div class="col-sm-2 control-label">
                Year:
              </div>

              <div class="col-sm-4">
                <select class="form-control" name="paper_year">
                  <option
                    <?php
                      if( !empty($_GET["advance_search"]) ) {
                        if( $_GET["paper_year"]=="all" ) {
                          echo "selected";
                        }
                      }
                    ?>>all</option>
                  <?php
                    for($initYear = date('Y'); $initYear>=2010; $initYear--) {
                      if( !empty($_GET["advance_search"]) ) {
                        if( $_GET["paper_year"]==$initYear ) {
                          echo "<option selected>$initYear</option>\n";
                        } else {
                          echo "<option>$initYear</option>\n";
                        }
                      } else {
                        echo "<option>$initYear</option>\n";
                      }
                    }
                  ?>
                </select>
              </div>

            </div>

            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-success">
                  <span class="glyphicon glyphicon-search"></span> Search with Filter</button>
              </div>
            </div>

          </form>
        </div>

      </div>

      <div class="row">
        <div class="col-md-12">

          <table class="table table-hover table-striped">
            <thead>
              <tr class="info">
                <th>Paper Title</th>
                <th>Author</th>
                <th>Type</th>
                <th>Detail</th>
              </tr>
            </thead>
            <tbody>
              <?php
                // set sql
                $sql = "SELECT * FROM research ";
                if(!empty($_GET["search_keyword"])) {
                  $key = str_replace("+"," ",$_GET["search_keyword"]);
                  $sql .= "where title like '%$key%'
                    or author_name_th like '%$key%'
                    or author_name_en like '%$key%'
                    or department like '%$key%'
                    or year(journal_accepted_date) = '$key'
                    or year(conference_start_date) = '$key'
                    or journal_published_year = '$key'";
                }
                if(!empty($_GET["advance_search"])) {
                  $sql .= "where 1=1 ";
                  if($_GET["paper_title"] != "") {
                    $key = $_GET["paper_title"];
                    $sql .= "and title like '%$key%' ";
                  }

                  if($_GET["paper_author"]  != "") {
                    $key = $_GET["paper_author"];
                    $sql .= "and (author_name_th like '%$key%' ";
                    $sql .= "or author_name_en like '%$key%') ";
                  }

                  if(!empty($_GET["options_journal"]) || !empty($_GET["options_conference"])) {
                    if(!empty($_GET["options_journal"]) && !empty($_GET["options_conference"])) {

                    } else if (!empty($_GET["options_journal"])) {
                      $sql .= "and research_type = 'journal' ";
                    } else if (!empty($_GET["options_conference"])) {
                      $sql .= "and research_type = 'conference' ";
                    }
                  }

                  if(!empty($_GET["options_international"]) || !empty($_GET["options_national"])) {
                    if(!empty($_GET["options_international"]) && !empty($_GET["options_national"])) {

                    } else if (!empty($_GET["options_international"])) {
                      $sql .= "and (journal_type = 'international' or conference_location_type = 'international')";
                    } else if (!empty($_GET["options_national"])) {
                      $sql .= "and (journal_type = 'national' or conference_location_type = 'national')";
                    }
                  }

                  if($_GET["paper_year"] == "all") {

                  } else {
                    $paper_year = $_GET["paper_year"];
                    $sql .= "and (year(journal_accepted_date) = '$paper_year'
                      or year(conference_start_date) = '$paper_year'
                      or journal_published_year = '$paper_year')";
                  }

                }
                $result_for_json = array();
                $result = mysqli_query($con, $sql);
                if (!$result) {
                  die('Error: ' . mysqli_error($con));
                } else {
                  while($row = mysqli_fetch_array($result)) {
                    $result_for_json[] = $row;
                    ?>
                    <tr>
                      <td>
                        <?php echo $row['title']; ?>
                      </td>
                      <td>
                        <?php echo $row['author_name_th']; ?><br/>
                        <?php echo $row['author_name_en']; ?>
                      </td>
                      <td>
                        <?php
                          if($row['research_type']=="journal") {
                            echo "Journal";
                          } else {
                            echo "Conference";
                          }
                        ?>
                      </td>
                      <td>
                        <!-- Button trigger modal -->
                        <button class='btn btn-xs btn-info' data-toggle='modal' data-target='#myModalDetail' onclick='setDataForDetail("<?php echo $row['id']; ?>")'>
                          <span class='glyphicon glyphicon-th-list'></span> Detail
                        </button>
                      </td>
                    </tr>
                    <?php
                  }
                }

                ?>
                <script>
                  researchs = <?php echo json_encode($result_for_json) ?>;
                  //console.log("count" + research.length);
                </script>
                <?php
              ?>
            </tbody>
          </table>

        </div>
      </div>

    </div>

    <!-- Detail Modal -->
    <div class="modal fade bs-example-modal-lg" id="myModalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabelDetail" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabelDetail">Detail</h4>
          </div>
          <div class="modal-body" id="mySmallModalBodyDetail">
            <div>
              <p>
                <strong>Title:</strong><br/>
                <span id="researchTitleDetail"></span>
              </p>
            </div>

            <div>
              <p>
                <strong>For Student Graduation:</strong><br/>
                <span id="researchTitleDetailForStudentGraduation"></span>
              </p>
            </div>

            <div>
              <p>
                <strong>Author:</strong><br/>
                <span id="researchTitleDetailAuthor"></span>
              </p>
            </div>

            <div>
              <p>
                <strong>Corresponding:</strong><br/>
                <span id="researchTitleDetailCorresponding"></span>
              </p>
            </div>

            <div>
              <p>
                <strong>Reference:</strong><br/>
                <span id="researchTitleDetailReference"></span>
              </p>
            </div>

            <div id="researchTitleDetailForJournal">

              <div>
                <p>
                  <strong>Journal Title:</strong><br/>
                  <span id="researchTitleDetailJournalTitle"></span>
                </p>
              </div>

              <div>
                <p>
                  <strong>National / International:</strong><br/>
                  <span id="researchTitleDetailJournalNationalInternational"></span>
                  <span id="researchTitleDetailJournalNationalInternationalDetail"></span>
                </p>
              </div>

              <div>
                <p>
                  <strong>Published / Inpress:</strong><br/>
                  <span id="researchTitleDetailJournalPublishedInpress"></span>
                  <span id="researchTitleDetailJournalPublishedInpressDetail"></span>
                </p>
              </div>

            </div>

            <div id="researchTitleDetailForProceeding">


              <div>
                <p>
                  <strong>Conference Name:</strong><br/>
                  <span id="researchTitleDetailConferenceName"></span>
                </p>
              </div>

              <div>
                <p>
                  <strong>Venue:</strong><br/>
                  <span id="researchTitleDetailVenue"></span>
                </p>
              </div>

              <div>
                <p>
                  <strong>Conference Start Date:</strong><br/>
                  <span id="researchTitleDetailStartDate"></span>
                </p>
              </div>

              <div>
                <p>
                  <strong>Conference End Date:</strong><br/>
                  <span id="researchTitleDetailEndDate"></span>
                </p>
              </div>

              <div>
                <p>
                  <strong>Page From:</strong><br/>
                  <span id="researchTitleDetailPageFrom"></span>
                </p>
              </div>

              <div>
                <p>
                  <strong>Page To:</strong><br/>
                  <span id="researchTitleDetailPageTo"></span>
                </p>
              </div>

              <div>
                <p>
                  <strong>National / International:</strong><br/>
                  <span id="researchTitleDetailNationalInternational"></span>
                </p>
              </div>

              <div>
                <p>
                  <strong>Oral / Poster:</strong><br/>
                  <span id="researchTitleDetailOralPoster"></span>
                </p>
              </div>

            </div>

            <div>
              <p>
                <a id="researchTitleDetailAttFile" href="#" class='btn btn-primary' target="_blank">
                  <span class='glyphicon glyphicon-download'></span> Download
                </a>
              </p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div> <!-- end Detail Modal -->




  </body>
</html>
