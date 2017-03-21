<!--spaghettified for now, improvements to come-->

<?php
    $db = mysqli_connect('127.0.0.1','','','slovak_english') or die('Error connecting to MySQL server.');
?>
<!--Random numbers should be generated by the highest and lowest ids in the db-->
<!--TODO: enhance, can set seed?-->
<!--TODO: Move inline styles to css-->
<!--This will determine what card to show-->
<!--Separate this out to be reused-->
<?php
    $select_range_q = "SELECT min(id) as lowest_id, max(id) as highest_id FROM s_e_dictionary";
    $range_result = mysqli_query($db, $select_range_q) or die('Error querying database.');
    $range_row = mysqli_fetch_assoc($range_result);
    $lang_entr = rand($range_row['lowest_id'], $range_row['highest_id']);
?>


<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" src="//normalize-css.googlecode.com/svn/trunk/normalize.css">
        <link rel="stylesheet" href="../slovak-english/css/bootstrap.min.css" />
        <link rel="stylesheet" href="../slovak-english/css/style.css?v=1.1" />
    </head>
    <body>
    <div class='container'>
    <div class='row'>
        <div class='col-md-2'>
        </div>
        <div class='col-md-8 text-center'>
        <h1>Language Flash!</h1>
        <h2 style='margin-top: 0px;'>Slovak</h2>
        </div>
        <div class='col-md-2'>
        </div>
    </div>
    <div class='row no-space-tb'>
        <div class='col-md-2'>
        </div>
        <div class='col-md-8 text-center'>
        <h3 style='margin-top: 0px;'>Be sure to include punctuation and capitalization</h3>
        <h5>Say the Slovak word, then type the English word.<br>
            If your answer is correct, Good Job! in Slovak will display. Otherwise, keep trying.<br>
            Press [enter] to get a new word.</h5>
        </div>
        <div class='col-md-2'>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-1'>
        </div>
        <div class='col-md-8 raised-box'>
            <?php
                //Step2
                $select_word_q = "SELECT * FROM s_e_dictionary WHERE id=".$lang_entr;
                $word_result = mysqli_query($db, $select_word_q) or die('Error querying database.');
                $word_row = mysqli_fetch_assoc($word_result);
                echo '<div class="row"><h3>'
                        .$word_row['slovak']
                        .'</h3>
                      </div>
                      <div class="row">
                         <img id="image-hint" style="display: none;" class="img-plain img-responsive" src="../slovak-english/images/'
                         .$word_row['image']
                          .'">
                          <input type="button" id="show-hide-image" value="Show Image Hint" />
                      </div>';
                      $english_answer = $word_row['english'];
                      $correct_answer = "Dobrá práca!";

                echo
                '<div class="row">
                   <input type="text" style="width: 300px; height: 45px; margin: 15px;" id="english_entry" type="text" placeholder="Type your English translation" autofocus /input>
                </div>
                <div class="row text-center">
                  <div style="display: none;" id="english_answer">'
                   .$english_answer.'
                  </div>
                </div>
                <div class="row text-center">
                  <div style="display: none; color: blue; font-size: 36px;" id="keep_trying">
                    Keep trying!
                  </div>
                </div>
                <div class="row text-center">
                  <div style="display: none; color: blue; font-size: 24px;" id="correct_answer">'
                    .$correct_answer.' <br> Press enter for another word!
                  </div>
                </div>
              </div>
              <div class="col-md-1">
              </div>
            </div>
          </div>';
            mysqli_close($db);
            ?>
            <script src="../slovak-english/js/jquery-1.12.4.min.js"></script>
            <script src="../slovak-english/js/bootstrap.min.js"></script>
            <script>

                $(function() {
                    $("#english_entry").focus();
                });

                $("#english_entry").keyup(function(event) {
                    var english_answer = $.trim($("#english_answer").text());
                    var english_entry = $.trim($("#english_entry").val());
                    if (english_entry === english_answer) {
                        console.log('good!');
                        $("#correct_answer").css("display", "inline-block");
                        $("#keep_trying").css("display", "none");
                    }
                    else {
                        $("#keep_trying").css("display", "inline-block");
                    };
                });


                $("#show-hide-image").click(function(event) {
                    var imageButtonValue = $.trim($("#show-hide-image").attr("value"));
                    console.log("image-button-value: " + imageButtonValue);
                    var english_entry = $.trim($("#english_entry").val());
                    if (imageButtonValue === "Show Image Hint") {
                        $("#show-hide-image").attr("value", "Hide Image");
                        $("#image-hint").css("display", "inline-block");
                    }
                    else if (imageButtonValue === "Hide Image") {
                        $("#show-hide-image").attr("value", "Show Image Hint");
                        $("#image-hint").css("display", "none");
                    };
               });


                /* TODO: Instead of reload page, AJAX to get new word */
                $("#english_entry").keydown(function(event) {
                    if (event.which == 13) {
                        location.reload(true);
                    }
                });
            </script>
    </body>
</html>