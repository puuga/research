<?php //input_form.php ?>

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
        chemistry:"ภาควิชาเคมี",
        physics:"ภาควิชาฟิสิกส์",
        biology:"ภาควิชาชีววิทยา",
        math:"ภาควิชาคณิตศาสตร์",
        csit:"ภาควิชาวิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ"
      };

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

      $(document).ready(function(){

        var researchersList = $("#researchers");
        //console.log(""+researcher_data.researcher[0].department);
        for(var i=0; i<researcher_data.researcher.length; i++) {
					var opt = $("<option/>").attr("value", researcher_data.researcher[i].name);
					researchersList.append(opt);
				}

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
        temp += "<label><input type='checkbox' name='isFirstResearcher"+researcherAmount+"' value='true'>Firstname</label>";
        temp += "<label><input type='checkbox' name='isCorresponseding"+researcherAmount+"' value='true'>corresponseding</label>";
        temp += "<br/>";
        temp += "<input list='departments' name='department"+researcherAmount+"' size='80' placeholder='ภาควิชา'/>";
        temp += "</div>";

        $("#researcher").append(temp);

        //check
        count = $('[name=researcher_name]');
        console.log("count+ ="+count.length);
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
      }

      function setDepartment(name,inputname) {
        console.log("name ="+name);
        console.log("inputname ="+inputname);
        inputname = inputname.replace("researcher", "department");
        for(var i=0; i<researcher_data.researcher.length; i++) {
          console.log("i ="+i);
          if(researcher_data.researcher[i].name == name) {
            $("[name="+inputname+"]").val(researcher_data.researcher[i].department);
            return;
          }
        }

      }
    </script>
  </head>
  <body>
    <div class="container">
      <div class="title-banner">Science Research System</div>
      <div>
        <form method="get">
          <div class="mouse-focus obj-display">
            Paper Title: <input type="text" name="research_name" size='100'/>
          </div>
          <div class="mouse-focus obj-display">
            <label><input type="checkbox" name="isStudentProduct" value="true">For Student Graduation: </label>
          </div>
          <div></div>
          <div class="mouse-focus obj-display">
            Authur<br/>
            <div id="researcher">
              <div class="researcher_display">
              <input list="researchers" name="researcher0" size='80' oninput="setDepartment(this.value,this.name)" placeholder="Authur Name"/>
              <label>
                <input type="checkbox" name="isFirstResearcher0" value="true">firstname
              </label>
              <label>
                <input type="checkbox" name="isCorresponseding0" value="true">corresponseding
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
          <div></div>
          <div class="mouse-focus obj-display">
            Type:
            <label><input id="radio_journal" type="radio" name="type" value="journal" />Journal</label>
            <label><input id="radio_conference" type="radio" name="type" value="conference" />Proceeding Paper</label>
          </div>
          <div>
            <div id="journal_form" style="display: none;">
              <div class="mouse-focus obj-display">
                Journal Name:
                <input list="journal_names" name="journal_name" size='80'/>
                <datalist id="journal_names">
                  <option value="aaa" />
                  <option value="bbb" />
                  <option value="ccc" />
                  <option value="ddd" />
                  <option value="eee" />
                  <option value="fff" />
                </datalist>
              </div>
              <div></div>
              <div></div>
              <div class="mouse-focus obj-display">
                <label><input id="radio_journal_type_national" type="radio" name="journal_type" value="national" />National</label>
                <label><input id="radio_journal_type_international" type="radio" name="journal_type" value="international" />Inter-National</label>

                <div></div>
              </div>



              <div id="journal_national" class="mouse-focus obj-display" style="display: none;">
                <label><input type="radio" name="journal_national_group" value="TCI กลุ่ม 1" />TCI group 1</label> <br/>
                <label><input type="radio" name="journal_national_group" value="TCI กลุ่ม 2" />TCI group 2</label> <br/>
                <label><input type="radio" name="journal_national_group" value="none" />none</label> <br/>
              </div>

              <div id="journal_international" class="mouse-focus obj-display" style="display: none;">
                <input type="checkbox" name="journal_international_group" value="ISI">ISI <br/>
                <input type="checkbox" name="journal_international_group" value="SCOPUS">SCOPUS <br/>
                <input type="checkbox" name="journal_international_group" value="SJR">SJR
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
                <label><input id="radio_journal_type_public" type="radio" name="journal_type_ingress" value="public" />Published</label>
                <label><input id="radio_journal_type_inpress" type="radio" name="journal_type_ingress" value="inpress" />Inpress</label>
              </div>
              <div id="journal_type_public" class="mouse-focus obj-display" style="display: none;">
                vol no: <input type="text" name="journal_vol"/><br/>
                issue: <input type="text" name="journal_issue"/><br/>
                number: <input type="text" name="journal_number"/><br/>
                page: <input type="text" name="journal_page_start"/> To: <input type="text" name="journal_page_end"/><br/>
                DOI no: <input type="text" name="journal_doi_no"/><br/>
                accepted date: <input type="month" name="journal_accepted_date"/><br/>
                published date: <input type="month" name="journal_published_date"/><br/>
              </div>
              <div id="journal_type_inpress" class="mouse-focus obj-display" style="display: none;">
                journal_type_inpress
              </div>
            </div>

            <div id="conference_form" style="display: none;">
              <div class="mouse-focus obj-display">
                Conference Name: <input type="text" name="conference_name" size='80'/>
              </div>
              <div class="mouse-focus obj-display">
                Address: <input type="text" name="conference_address" size='80'/>
              </div>
              <div class="mouse-focus obj-display">
                <div class="obj-display">วันที่จัด:</div>
                <div class="obj-display">
                  Start Date: <input type="date" name="conference_start_date"/><br/>
                  End Date: <input type="date" name="conference_end_date"/><br/>
                </div>
              </div>
              <div></div>
              <div class="mouse-focus obj-display">
                accepted date: <input type="month" name="journal_accepted_date"/><br/>
              </div>
              <div></div>
              <div class="mouse-focus obj-display">
                <label><input type="radio" name="conference_location_type" value="national" />National</label><br/>
                <label><input type="radio" name="conference_location_type" value="international" />Inter-National</label><br/>
              </div>
              <div class="mouse-focus obj-display">
                <label><input type="radio" name="conference_type" value="oral" />oral</label><br/>
                <label><input type="radio" name="conference_type" value="poster" />poster</label><br/>
              </div>
            </div>
          </div>
          <div></div>
          <div>
            Attach File: <input type="file" name="att_file" />
          </div>
          <div>
            Reference:<br/>
            <textarea rows="5" cols="100"></textarea>
            <div class="obj-display" style="background-color: orange;">
              เสาวณีย์ จำเดิมเผด็จศึก. (2534). การรักษาภาวะจับหืดเฉียบพลันในเด็ก. ใน สมศักดิ์ โล่ห์เลขา, ชลีรัตน์ ดิเรกวัฒชัย และ มนตรี ตู้จินดา (บรรณาธิการ), อิมมูโนวิทยาทางคลีนิคและโรคภูมิแพ้. (น. 99-103). กรุงเทพฯ: วิทยาลัย กุมารแพทย์แห่งประเทศไทย และสมาคมกุมารแพทย์แห่งประเทศไทย.
            </div>
          </div>
          <input type="submit" />
          <input type="reset" />
        </form>
      </div>
    </div>
  </body>
</html>
