<?php //input_form.php ?>
<?php include 'login_control.php'; ?>
<?php include 'db_connect.php'; ?>
<?php include "class_import.php"; ?>
<?php
  needAdminLevel(1);
?>
<!DOCTYPE html>
<!--
code by siwawes wongcharoen
-->
<html>
  <head>
    <meta charset="UTF-8">

    <title>Science Research System</title>

    <?php include 'head_tag.php'; ?>

    <!--<link rel="stylesheet" type="text/css" href="css/main_style.css">-->

    <script type="text/javascript">
      var researcherAmount = 0;

      var department = {
        chemistry:"เคมี",
        physics:"ฟิสิกส์",
        biology:"ชีววิทยา",
        math:"คณิตศาสตร์",
        csit:"วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ"
      };

      <?php
        //read researcher
        $sql = "SELECT * FROM researcher";
        $result = mysqli_query($con, $sql);

        $researchers = array();

        $researchers_name = array();

        while($row = mysqli_fetch_array($result)) {
          $researcherTH = array();
          $researcherTH["name"] = $row['name_th'];
          $researcherTH["department"] = $row['department_th'];
          $researcherEN = array();
          $researcherEN["name"] = $row['name_en'];
          $researcherEN["department"] = $row['department_en'];

          $researchers[] = $researcherTH;
          $researchers[] = $researcherEN;

          $temp = array();
          $temp["name_th"] = $row['name_th'];
          $temp["name_en"] = $row['name_en'];
          $researchers_name[] = $temp;
        }

        //mysqli_close($con);
        //read conference
        $sql = "SELECT distinct name FROM conference";
        $result = mysqli_query($con, $sql);

        $conferences = array();

        while($row = mysqli_fetch_array($result)) {
          $conference = array();
          $conference["name"] = $row['name'];

          $conferences[] = $conference;
        }

        //read journals
        $sql = "SELECT distinct name FROM journal";
        $result = mysqli_query($con, $sql);

        $journals = array();

        while($row = mysqli_fetch_array($result)) {
          $journal = array();
          $journal["name"] = $row['name'];

          $journals[] = $journal;
        }

      ?>
      /*
      var researcher_data = {
        researcher:[
          {name:"ศิวเวศวร์ วงษ์เจริญ", department:department.physics },
          {name:"Siwawes Wongcharoen", department:department.physics },
          {name:"จรัสศรี รุ่งรัตนาอุบล", department:department.csit },
          {name:"Jaratsri Rungrattanaubol", department:department.csit },
          {name:"ดวงเดือน อัศวสุธีรกุล", department:department.math },
          {name:"Duangduen Roongpiboonsopit", department:department.math }
        ]
      };
      */

      var researcher_data = JSON.parse('<?php echo json_encode($researchers); ?>');
      var conference_data = JSON.parse('<?php echo json_encode($conferences); ?>');
      var journal_data = JSON.parse('<?php echo json_encode($journals); ?>');

      var researchers_name = JSON.parse('<?php echo json_encode($researchers_name); ?>');

      $(document).ready(function(){

        // init researchersList
        var researchersList = $("#researchers");
        //console.log(""+researcher_data.researcher[0].department);
        for(var i=0; i<researcher_data.length; i++) {
					var opt = $("<option/>").attr("value", researcher_data[i].name);
					researchersList.append(opt);
				}

        // init departmentsList
        var departmentsList = $("#departments");
        var opt1 = $("<option/>").attr("value", department.chemistry);
        var opt2 = $("<option/>").attr("value", department.physics);
        var opt3 = $("<option/>").attr("value", department.biology);
        var opt4 = $("<option/>").attr("value", department.math);
        var opt5 = $("<option/>").attr("value", department.csit);
        departmentsList.append(opt1);
        departmentsList.append(opt2);
        departmentsList.append(opt3);
        departmentsList.append(opt4);
        departmentsList.append(opt5);

        // init conferencesList
        var conferencesList = $("#conference_names");
        //console.log("conference_data.length"+conference_data.length);
        for(var i=0; i<conference_data.length; i++) {
          var opt = $("<option/>").attr("value", conference_data[i].name);
          //console.log("conference_data[i].name"+conference_data[i].name);
          conferencesList.append(opt);
        }

        //init journal_names list
        var journalList = $("#journal_names");
        //console.log("conference_data.length"+conference_data.length);
        for(var i=0; i<journal_data.length; i++) {
          var opt = $("<option/>").attr("value", journal_data[i].name);
          //console.log("journal[i].name"+journal[i].name);
          journalList.append(opt);
        }


        $("#radio_journal").click( function() {
          $("#conference_form").hide();
          $("#journal_form").show();
        });

        $("#radio_conference").click( function() {
          $("#journal_form").hide();
          $("#conference_form").show();
        });

        $("#radio_journal_type_national").click( function() {
          $("#journal_international").hide();
          $("#journal_national").show();
        });

        $("#radio_journal_type_international").click( function() {
          $("#journal_national").hide();
          $("#journal_international").show();
        });

        $("input[value='SJR']").click( function() {
          $("#journal_international_group_sjr").toggle();
        });

        $("#radio_journal_type_public").click( function() {
          $("#journal_type_inpress").hide();
          $("#journal_type_public").show();
        });

        $("#radio_journal_type_inpress").click( function() {
          $("#journal_type_public").hide();
          $("#journal_type_inpress").show();
        });

      });

      function addResearcher() {
        //check
        var count = $('[name=researcher_name]');
        console.log("count+ ="+count.length);

        //
        researcherAmount++;

        var temp = "<div name='researcher_name'>";
        temp += "<div class='row'><br/>";
        temp += "<div class='col-md-10'>";
        temp += "<input list='researchers' name='researcher"+researcherAmount+"' class='form-control' oninput='setDepartment(this.value,this.name)' placeholder='Authur'/> ";
        temp += "<input list='departments' name='department"+researcherAmount+"' class='form-control' placeholder='department'/>";
        temp += "<input type='hidden' name='researcher"+researcherAmount+"_th' />";
        temp += "<input type='hidden' name='researcher"+researcherAmount+"_en' />";
        temp += "</div>";
        temp += "<div class='col-md-2'>";
        temp += "<div class='radio'>";
        temp += "<label><input type='radio' name='corresponding' value='"+researcherAmount+"'>Corresponding</label>";
        temp += "</div>";
        temp += "</div>";

        temp += "</div>";
        temp += "</div>";

        $("#researcher").append(temp);

        //check
        count = $('[name=researcher_name]');
        console.log("count+ ="+count.length);

        // set hidden
        $("#researcher_amount").val(researcherAmount);
      }

      function removeResearcher() {
        //check
        var count = $('[name=researcher_name]');
        if(count.length==0) {
          return;
        }
        console.log("count- ="+count.length);
        count[count.length-1].remove();

        //
        researcherAmount--;

        count = $('[name=researcher_name]');
        console.log("count- ="+count.length);

        // set hidden
        $("#researcher_amount").val(researcherAmount);
      }

      function setDepartment(name,inputname) {
        console.log("name ="+name);
        console.log("inputname ="+inputname);

        for(var i=0; i<researchers_name.length; i++) {
          if(researchers_name[i].name_th == name || researchers_name[i].name_en == name) {
            $("[name="+inputname+"_th]").val(researchers_name[i].name_th);
            $("[name="+inputname+"_en]").val(researchers_name[i].name_en);
            break;
          } else {
            $("[name="+inputname+"_th]").val(name);
            $("[name="+inputname+"_en]").val(name);
          }
        }


        inputname = inputname.replace("researcher", "department");
        for(var i=0; i<researcher_data.length; i++) {
          //console.log("i ="+i);
          if(researcher_data[i].name == name) {
            $("[name="+inputname+"]").val(researcher_data[i].department);
            break;
          }
        }

      }

      function setConferenceEndDateFromConferenceStartDate() {
        var startDate = new Date($("#conference_start_date").val());
        var endDate = new Date();
        endDate.setDate(startDate.getDate() + 1);
        //console.log("startDate:"+startDate);
        console.log("endDate:"+endDate.toLocaleDateString());

        var today = endDate;
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!

        var yyyy = today.getFullYear();
        if (dd<10) {
          dd = '0'+dd;
        }
        if (mm<10) {
          mm = '0'+mm;
        }
        today = yyyy+'-'+mm+'-'+dd;

        $("#conference_end_date").val(today);

        console.log("endDate:"+$("#conference_end_date").val());
      }

    </script>

    <style>
      body {
        padding-top: 10px;
        margin-bottom: 10px;
      }
    </style>

  </head>

  <body>
    <div class="container">

      <!--title row-->
      <div class="row bg-info">
        <div class="col-md-10">
          <h2>Add New Paper</h2>
        </div>
        <div class="col-md-2 text-right">
          <p></p>
          <a class="btn btn-danger" href="javascript:history.go(-1)" role="button">
            <span class="glyphicon glyphicon-remove"></span> Cancle
          </a>
        </div>
      </div>

      <form role="form" id="form1" method="post" action="input_form_process.php" enctype="multipart/form-data">

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="research_name">Paper Title:</label>
              <input type="text" class="form-control" name="research_name" id="research_name" placeholder="Paper Title" required/>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                <input type="checkbox" name="isStudentProduct" value="true">For Student Graduation:
              </label>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="form-group">
            <div class="col-md-12">
              <b>Authur Name:</b>
              <div id="researcher">

                <div class="row">
                  <div class="col-md-10">
                    <input list="researchers" name="researcher0" class="form-control" oninput="setDepartment(this.value,this.name)" placeholder="Authur Name"/>
                    <input list="departments" name="department0" class="form-control" placeholder="Deparment"/>
                    <input type="hidden" name="researcher0_th" />
                    <input type="hidden" name="researcher0_en" />
                  </div>
                  <div class="col-md-2">
                    <div class="radio">
                      <label>
                        <input type="radio" name="corresponding" value="0">Corresponding
                      </label>
                    </div>
                  </div>
                </div>

              </div>
              <br/>
              <div>
                <input type="button" class="btn btn-success" onclick="javascript:addResearcher()" value="+"/>
                <input type="button" class="btn btn-danger" onclick="javascript:removeResearcher()" value="-"/>
                <datalist id="researchers"></datalist>
                <datalist id="departments"></datalist>
              </div>
            </div>

            <div><input type="hidden" name="researcher_amount" id="researcher_amount" value=""/></div>

          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <br/>
            Type:
            <div class="radio">
              <label>
                <input id="radio_journal" type="radio" name="type" value="journal" />
                Journal
              </label>
            </div>
            <div class="radio">
              <label>
                <input id="radio_conference" type="radio" name="type" value="conference" />
                Proceedings
              </label>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">

            <div id="journal_form" style="display: none;">

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="journal_name">Journal Title:</label>
                    <input list="journal_names" name="journal_name" id="journal_name" class="form-control" />
                    <datalist id="journal_names"></datalist>
                  </div>
                </div>
              </div>

              <div class="row">

                <div class="col-md-2">
                  <div class="radio">
                    <label>
                      <input id="radio_journal_type_national" type="radio" name="journal_type" value="national" />
                      National
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input id="radio_journal_type_international" type="radio" name="journal_type" value="international" />
                      International
                    </label>
                  </div>
                </div>

                <div class="col-md-10">
                  <div id="journal_national" style="display: none;">
                    <div class="row">
                      <div class="col-md-2">
                        <div class="radio">
                          <label>
                            <input type="radio" name="journal_national_group" value="TCI group 1" />
                            TCI group 1
                          </label>
                        </div>
                        <div class="radio">
                          <label>
                            <input type="radio" name="journal_national_group" value="TCI group 2" />
                            TCI group 2
                          </label>
                        </div>
                        <div class="radio">
                          <label>
                            <input type="radio" name="journal_national_group" value="none" />none
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div id="journal_international" style="display: none;">
                    <div class="row">

                      <div class="col-md-2">
                        <div class="checkbox">
                          <label>
                            <input type="checkbox" name="is_journal_international_ISI" value="ISI">
                            ISI
                          </label>
                        </div>
                        <div class="checkbox">
                          <label>
                            <input type="checkbox" name="is_journal_international_SCOPUS" value="SCOPUS">
                            SCOPUS
                          </label>
                        </div>
                        <div class="checkbox">
                          <label>
                            <input type="checkbox" name="is_journal_international_SJR" value="SJR">
                            SJR
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div id="journal_international_group_sjr" style="display: none;">
                          <div class="radio">
                            <label>
                              <input type="radio" name="journal_international_group_sjr" value="Q1" />Q1
                            </label>
                          </div>
                          <div class="radio">
                            <label>
                              <input type="radio" name="journal_international_group_sjr" value="Q2" />Q2
                            </label>
                          </div>
                          <div class="radio">
                            <label>
                              <input type="radio" name="journal_international_group_sjr" value="Q3" />Q3
                            </label>
                          </div>
                          <div class="radio">
                            <label>
                              <input type="radio" name="journal_international_group_sjr" value="Q4" />Q4
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              </div>

              <div class="row">

                <div class="col-md-2">
                  <div class="radio">
                    <label>
                      <input id="radio_journal_type_public" type="radio" name="journal_type_progress" value="published" />
                      Published
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input id="radio_journal_type_inpress" type="radio" name="journal_type_progress" value="inpress" />
                      Inpress
                    </label>
                  </div>
                </div>

                <div class="col-md-6">

                  <div id="journal_type_public" class="form-horizontal" style="display: none;">

                    <div class="form-group">
                      <label for="journal_vol" class="col-sm-4 control-label">
                        Vol.:
                      </label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" name="journal_vol" id="journal_vol"/>
                      </div>
                      <br/>
                    </div>

                    <div class="form-group">
                      <label for="journal_issue" class="col-sm-4 control-label">
                        Issue No.:
                      </label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" name="journal_issue" id="journal_issue"/>
                      </div>
                      <br/>
                    </div>

                    <div class="form-group">
                      <label for="journal_number" class="col-sm-4 control-label">
                        Number:
                      </label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" name="journal_number" id="journal_number"/>
                      </div>
                      <br/>
                    </div>

                    <div class="form-group">
                      <label for="journal_page_start" class="col-sm-4 control-label">
                        From Page:
                      </label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" name="journal_page_start" id="journal_page_start"/>
                      </div>
                      <br/>
                    </div>

                    <div class="form-group">
                      <label for="journal_page_end" class="col-sm-4 control-label">
                        To Page:
                      </label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" name="journal_page_end" id="journal_page_end"/>
                      </div>
                      <br/>
                    </div>

                    <div class="form-group">
                      <label for="journal_doi_no" class="col-sm-4 control-label">
                        DOI no:
                      </label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" name="journal_doi_no" id="journal_doi_no"/>
                      </div>
                      <br/>
                    </div>

                    <div class="form-group">
                      <label for="journal_accepted_date" class="col-sm-4 control-label">
                        Accepted date:
                      </label>
                      <div class="col-sm-4">
                        <input type="month" class="form-control" name="journal_accepted_date" id="journal_accepted_date"/>
                      </div>
                      <br/>
                    </div>

                    <div class="form-group">
                      <label for="journal_published_month" class="col-sm-4 control-label">
                        Published month:
                      </label>
                      <div class="col-sm-4">
                        <select class="form-control" name="journal_published_month" id="journal_published_month">
                          <option value="January">January</option>
                          <option value="February">February</option>
                          <option value="March">March</option>
                          <option value="April">April</option>
                          <option value="May">May</option>
                          <option value="June">June</option>
                          <option value="July">July</option>
                          <option value="August">August</option>
                          <option value="September">September</option>
                          <option value="October">October</option>
                          <option value="November">November</option>
                          <option value="December">December</option>
                        </select>
                      </div>
                      <br/>
                    </div>

                    <div class="form-group">
                      <label for="journal_published_year" class="col-sm-4 control-label">
                        Published year:
                      </label>
                      <div class="col-sm-4">
                        <select class="form-control" id="journal_published_year" name="journal_published_year">
                        </select>
                        <script>
                          // setup year
                          var d = new Date();
                          var n = d.getFullYear();
                          var nMin = n-5;
                          var nMax = n+5;
                          for ( i=nMin; i<=nMax; i++ ) {
                            var temp = "<option value='"+i+"'>"+i+"</option>";
                            if ( i==n ) {
                              temp = "<option value='"+i+"' selected>"+i+"</option>";
                            }
                            $("#journal_published_year").append(temp);
                          }
                        </script>
                      </div>
                      <br/>
                    </div>

                  </div>

                  <div id="journal_type_inpress" style="display: none;">
                  </div>

                </div>

              </div>

            </div>

            <div id="conference_form" style="display: none;">

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="conference_name">Conference Name:</label>
                    <input list="conference_names" name="conference_name" id="conference_name" class="form-control"/>
                    <datalist id="conference_names"></datalist>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="conference_address">Venue:</label>
                    <input type="text" name="conference_address" id="conference_address" class="form-control"/>
                  </div>
                </div>
              </div>

              <div class="row form-horizontal">

                <div class="col-md-4">
                  Conference date:
                  <div class="form-group">
                    <label for="conference_start_date" class="col-sm-6 control-label">Start Date:</label>
                    <div class="col-sm-6">
                      <input type="date" class="form-control" id="conference_start_date" name="conference_start_date" oninput="setConferenceEndDateFromConferenceStartDate()"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="conference_end_date" class="col-sm-6 control-label">End Date:</label>
                    <div class="col-sm-6">
                      <input type="date" class="form-control" id="conference_end_date" name="conference_end_date"/>
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  Page:
                  <div class="form-group">
                    <label for="conference_page_start" class="col-sm-3 control-label">From:</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="conference_page_start" id="conference_page_start"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="conference_page_end" class="col-sm-3 control-label">To:</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="conference_page_end" id="conference_page_end"/>
                    </div>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="radio">
                    <label>
                      <input type="radio" name="conference_location_type" value="national" />
                      National
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="conference_location_type" value="international" />
                      International
                    </label>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="radio">
                    <label>
                      <input type="radio" name="conference_type" value="oral" />
                      Oral
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="conference_type" value="poster" />
                      Poster
                    </label>
                  </div>
                </div>

              </div>

            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="att_file">Upload File: (.pdf file)</label>
              <input type="file" name="att_file" id="att_file" accept="application/pdf" />
              <p class="help-block">Accept only .pdf file</p>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="reference">Reference:</label>
              <textarea name="reference" id="reference" rows="5" class="form-control"></textarea>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-body bg-info">
                <p><b>Reference Format:</b></p>
                <p>
                  S. Suksern, S.V. Meleshko. Applications of tangent transformations to the linearization problem of fourth-order ordinary differential equations. Classical Analysis and ODEs. 26 Page
                </p>
              </div>
            </div>

          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
              <input type="submit" class="btn btn-primary" >
              <input type="reset" class="btn btn-warning" />
          </div>
        </div>

      </form>

      <script type="text/javascript">
        // disable submit button
        // btnSubmit
        $( "#form1" ).submit(function( event ) {
          //alert( "Handler for .submit() called." );
          //event.preventDefault();
          $( "#btnSubmit" ).attr("disabled", "disabled");
          //event.preventDefault();
        });
      </script>

    </div>
  </body>
</html>
