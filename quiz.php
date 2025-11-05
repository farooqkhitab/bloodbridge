<?php
require 'check_login.php'; // This ensures only logged-in users can see this page
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- --------------------------------------------------------------- -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eligibility criteria</title>
    
    <link rel="stylesheet" href="css/quiz.css">
    <!-- <link rel="stylesheet" href="css/index.css "> -->
</head>









<body>
    <!-- navbar -->
    <?php include 'navbar.php'; ?>
    <!-- Navbar end  -->

    <main>
        <div class="quiz-container" id="quiz-container">

            <div class="quiz-content">
                <div class="quiz-header">
                    <div id="quiz-step" class="quiz-step"></div>
                    <img id="question-icon" src="" alt="Icon">
                    <div id="quiz-question" class="quiz-question"></div>
                </div>

                <div class="quiz-question-area">
                    <div class="quiz-answer-area">
                        <button id="yes-btn" class="quiz-answer-btn">Yes</button>
                        <button id="no-btn" class="quiz-answer-btn">No</button>
                    </div>
                </div>

                <div class="quiz-nav">
                    <button id="prev-btn" class="quiz-nav-btn hidden">Previous</button>
                    <button id="next-btn" class="quiz-nav-btn hidden">Next</button>
                </div>
            </div>



            <div class="quiz-info-area">
                <div class="quiz-info-title">Why do we ask this?</div>
                <div id="quiz-info" class="quiz-info-content"></div>
            </div>
        </div>
    </main>

    <script src="js/quiz.js"></script>

</body>

</html>