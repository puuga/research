<?php //input_form.php ?>

<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<!--
code by siwawes wongcharoen
-->
<html>
  <head>
    <meta charset="UTF-8">

    <title>Science Research System</title>

    <link rel="stylesheet" type="text/css" href="css/main_style.css">

    <script src="script/jquery-2.1.1.min.js"></script>
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

        var temp = "<div name='researcher_name' class='researcher_display'>";
        temp += "<input list='researchers' name='researcher"+researcherAmount+"' size='80' oninput='setDepartment(this.value,this.name)' placeholder='Authur'/> ";
        temp += "<input type='hidden' name='researcher"+researcherAmount+"_th' />";
        temp += "<input type='hidden' name='researcher"+researcherAmount+"_en' />";
        temp += "<label><input type='radio' name='corresponding' value='"+researcherAmount+"'>Corresponding</label>";
        temp += "<br/>";
        temp += "<input list='departments' name='department"+researcherAmount+"' size='80' placeholder='department'/>";
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
          }
        }


        inputname = inputname.replace("researcher", "department");
        for(var i=0; i<researcher_data.length; i++) {
          //console.log("i ="+i);
          if(researcher_data[i].name == name) {
            $("[name="+inputname+"]").val(researcher_data[i].department);
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
  </head>
  <body>
    <div class="container">
      <div class="title-banner">Science Research System</div>
      <div>
        <form id="form1" method="post" action="input_form_process.php" enctype="multipart/form-data">
          <div class="mouse-focus obj-display">
            Paper Title: <input type="text" name="research_name" size='100' required/>
          </div>
          <div class="mouse-focus obj-display">
            <label><input type="checkbox" name="isStudentProduct" value="true">For Student Graduation: </label>
          </div>
          <div></div>
          <div class="mouse-focus obj-display">
            Authur Name<br/>
            <div id="researcher">
              <div class="researcher_display">
              <input list="researchers" name="researcher0" size='80' oninput="setDepartment(this.value,this.name)" placeholder="Authur Name"/>
              <input type="hidden" name="researcher0_th" />
              <input type="hidden" name="researcher0_en" />
              <!--
              <label>
                <input type="checkbox" name="isFirstResearcher0" value="true">First name
              </label>
              -->
              <label>
                <input type="radio" name="corresponding" value="0">Corresponding
              </label>
              <br/>
              <input list="departments" name="department0" size='80' placeholder="Deparment"/>
              </div>

            </div>
            <br/>
            <input type="button" onclick="javascript:addResearcher()" value="+"/>
            <input type="button" onclick="javascript:removeResearcher()" value="-"/>
            <datalist id="researchers">
            </datalist>
            <datalist id="departments">
            </datalist>
          </div>
          <div><input type="hidden" name="researcher_amount" id="researcher_amount" value=""/></div>
          <div class="mouse-focus obj-display">
            Type:
            <label><input id="radio_journal" type="radio" name="type" value="journal" />Journal</label>
            <label><input id="radio_conference" type="radio" name="type" value="conference" />Proceedings</label>
          </div>
          <div>
            <div id="journal_form" style="display: none;">
              <div class="mouse-focus obj-display">
                Journal Title:
                <input list="journal_names" name="journal_name" size='80'/>
                <datalist id="journal_names">
                </datalist>
              </div>
              <div></div>
              <div></div>
              <div class="mouse-focus obj-display">
                <label>
                  <input id="radio_journal_type_national" type="radio" name="journal_type" value="national" />
                  National
                </label>
                <label>
                  <input id="radio_journal_type_international" type="radio" name="journal_type" value="international" />
                  International
                </label>

                <div></div>
              </div>



              <div id="journal_national" class="mouse-focus obj-display" style="display: none;">
                <label><input type="radio" name="journal_national_group" value="TCI group 1" />TCI group 1</label> <br/>
                <label><input type="radio" name="journal_national_group" value="TCI group 2" />TCI group 2</label> <br/>
                <label><input type="radio" name="journal_national_group" value="none" />none</label> <br/>
              </div>

              <div id="journal_international" class="mouse-focus obj-display" style="display: none;">
                <input type="checkbox" name="is_journal_international_ISI" value="ISI">ISI <br/>
                <input type="checkbox" name="is_journal_international_SCOPUS" value="SCOPUS">SCOPUS <br/>
                <input type="checkbox" name="is_journal_international_SJR" value="SJR">SJR
                <span>
                  <div id="journal_international_group_sjr" style="display: none;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" name="journal_international_group_sjr" value="Q1" />Q1</label> <br/>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" name="journal_international_group_sjr" value="Q2" />Q2</label> <br/>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" name="journal_international_group_sjr" value="Q3" />Q3</label> <br/>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" name="journal_international_group_sjr" value="Q4" />Q4</label> <br/>
                  </div>
                </span>
              </div>

              <div></div>

              <div class="mouse-focus obj-display">
                <label><input id="radio_journal_type_public" type="radio" name="journal_type_progress" value="public" />Published</label>
                <label><input id="radio_journal_type_inpress" type="radio" name="journal_type_progress" value="inpress" />Inpress</label>
              </div>
              <div id="journal_type_public" class="mouse-focus obj-display" style="display: none;">
                <div class="subBox">
                  <div class="left">Vol.:</div>
                  <div class="right"><input type="text" name="journal_vol"/></div>
                  <br/>
                </div>
                <br/>

                <div class="subBox">
                  <div class="left">Issue No.:</div>
                  <div class="right"><input type="text" name="journal_issue"/></div>
                  <br/>
                </div>
                <br/>

                <div class="subBox">
                  <div class="left">Number:</div>
                  <div class="right"><input type="text" name="journal_number"/></div>
                  <br/>
                </div>
                <br/>

                <div class="subBox">
                  <div class="left">Page:</div>
                  <div class="right">
                    <div class="left">From:</div>
                    <div class="right"><input type="text" name="journal_page_start"/></div>
                    <br/>
                    <div class="left">To:</div>
                    <div class="right"><input type="text" name="journal_page_end"/></div>
                  </div>
                  <br/>
                  <br/>
                </div>
                <br/>

                <div class="subBox">
                  <div class="left">DOI no:</div>
                  <div class="right"><input type="text" name="journal_doi_no"/></div>
                  <br/>
                </div>
                <br/>

                <div class="subBox">
                  <div class="left">Accepted date:</div>
                  <div class="right"><input type="month" name="journal_accepted_date"/></div>
                  <br/>
                </div>
                <br/>

                <div class="subBox">
                  <div class="left">Published month:</div>
                  <div class="right">
                    <select name="journal_published_month">
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
                <br/>

                <div class="subBox">
                  <div class="left">Published year:</div>
                  <div class="right">
                    <select id="journal_published_year" name="journal_published_year">
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
                <br/>

              </div>

              <div id="journal_type_inpress" class="mouse-focus obj-display" style="display: none;">
                journal_type_inpress
              </div>
            </div>

            <div id="conference_form" style="display: none;">
              <div class="mouse-focus obj-display">
                Conference Name:
                  <input list="conference_names" name="conference_name" size='120'/>
                  <datalist id="conference_names"></datalist>
              </div>
              <div class="mouse-focus obj-display">
                Venue: <input type="text" name="conference_address" size='80'/>
              </div>
              <div class="mouse-focus obj-display">
                <div class="obj-display">
                  Conference date:
                </div>
                <div class="obj-display">
                  Start Date: <input type="date" id="conference_start_date" name="conference_start_date" oninput="setConferenceEndDateFromConferenceStartDate()"/><br/>
                  End Date: <input type="date" id="conference_end_date" name="conference_end_date"/><br/>
                </div>
              </div>
              <div></div>
              <div class="mouse-focus obj-display">
                Page: <input type="text" name="conference_page_start"/> To: <input type="text" name="conference_page_end"/><br/>
              </div>
              <div></div>
              <div class="mouse-focus obj-display">
                <label><input type="radio" name="conference_location_type" value="national" />National</label><br/>
                <label><input type="radio" name="conference_location_type" value="international" />Inter-National</label><br/>
              </div>
              <div></div>
              <div class="mouse-focus obj-display">
                <label><input type="radio" name="conference_type" value="oral" />oral</label><br/>
                <label><input type="radio" name="conference_type" value="poster" />poster</label><br/>
              </div>
            </div>
          </div>
          <div></div>
          <div class="mouse-focus obj-display">
            Uplaod File: (.pdf file)<input type="file" name="att_file" accept="application/pdf" required/>
          </div>
          <div> </div>
          <div class="mouse-focus obj-display">
            Reference:<br/>
            <textarea name="reference" rows="5" cols="100"></textarea>

          </div>
          </div>
            <div class="obj-display-background">
              <p><b>Reference Format:</b></p>
              <p>
                S. Suksern, S.V. Meleshko. Applications of tangent transformations to the linearization problem of fourth-order ordinary differential equations. Classical Analysis and ODEs. 26 Page
              </p>
            </div>
          <div>

          <input type="submit" />
          <input type="reset" />
        </form>
      </div>

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
