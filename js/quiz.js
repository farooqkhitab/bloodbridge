
const questions = [
  {
    question: "Are you between the ages of 18 and 65?",
    info: "Blood donation requires physical maturity and optimal health, typically ensured within this age range.",
    image: "img.png",
    error: "You are not eligible for donation as you do not fall within the required age range (18 to 65 years)."
  },
  {
    question: "Do you weigh at least 50 kg?",
    info: "Weight below 50 kg could increase the risk of adverse reactions during blood donation.",
    image: "img.png",
    error: "You do not meet the minimum weight requirement of 50 kg, which is necessary to ensure safe blood donation."
  },
  {
    question: "Are you currently in good health and free of acute or chronic illnesses?",
    info: "Donating blood while unwell could put both the donor and recipient at risk.",
    image: "img.png",
    error: "You are not eligible as donors must be in good health and free from acute or chronic illnesses at the time of donation."
  },
  {
    question: "Do you have healthy skin without diseases or scars at the phlebotomy site (arms/forearms)?",
    info: "Skin issues or scars may indicate risks such as infection or unsafe blood donation practices.",
    image: "img.png",
    error: "You cannot donate blood due to skin issues or scars at the phlebotomy site, which could indicate infections or unsafe conditions."
  },
  {
    question: "Do your hemoglobin levels meet the required threshold (125 g/L for females, 130 g/L for males)?",
    info: "Low hemoglobin levels could result in anemia or fatigue after donation.",
    image: "img.png",
    error: "Your hemoglobin levels do not meet the required threshold, which could result in anemia or other health issues after donation."
  },
  {
    question: "Are your vital signs (blood pressure, pulse, temperature) within normal limits without medication?",
    info: "Normal vital signs indicate the donor’s readiness for safe blood donation.",
    image: "img.png",
    error: "Your vital signs are not within the normal range, which is necessary to ensure safety during the donation process."
  },
  {
    question: "Are you free from chronic diseases such as cancer, heart disease, or diabetes requiring insulin?",
    info: "Chronic diseases can pose risks to both the donor’s health and the recipient's safety.",
    image: "img.png",
    error: "You are not eligible for donation as chronic diseases like cancer, heart disease, or insulin-dependent diabetes pose risks to both donor and recipient health."
  },
  {
    question: "Are you free from transmissible diseases such as HIV/AIDS, Hepatitis B or C, or syphilis?",
    info: "These conditions can be transmitted through blood and compromise recipient safety.",
    image: "img.png",
    error: "You are not eligible to donate as transmissible diseases like HIV or Hepatitis could compromise the safety of the blood recipient."
  },
  {
    question: "Have you avoided injecting recreational drugs and other at-risk sexual behaviors in the past 12 months?",
    info: "Such activities increase the risk of bloodborne infections, potentially endangering recipients.",
    image: "img.png",
    error: "You cannot donate blood due to high-risk behaviors that increase the likelihood of infections being transmitted through blood."
  },
  {
    question: "Have you avoided recent travel to areas with a high risk of infectious diseases in the past 12 months?",
    info: "Travel to endemic areas increases the likelihood of exposure to diseases like malaria, compromising blood safety.",
    image: "img.png",
    error: "You are not eligible to donate as recent travel to high-risk areas increases the chance of transmitting infectious diseases."
  },
  {
    question: "Have you avoided tattoos or body piercings within the past 6 months?",
    info: "Tattooing or body piercing within the past 6 months can increase the risk of infections that may compromise blood safety.",
    image: "img.png",
    error: "You are not eligible to donate blood if you have had a tattoo or body piercing within the past 6 months, as it may pose a risk of infection."
  },
  {
    question: "Have you avoided major dental work within the past 1 month?",
    info: "Major dental procedures may leave the body temporarily prone to infections, which could compromise blood safety.",
    image: "img.png",
    error: "You are not eligible to donate blood if you have had major dental work within the past 1 month, as your body may still be recovering."
  },
  {
    question: "Have you avoided vaccinations within the past 15 days (or 1 year for rabies)?",
    info: "Certain vaccines, such as rabies or others administered recently, can temporarily affect your eligibility to donate blood due to potential interactions or reactions.",
    image: "img.png",
    error: "You are not eligible to donate blood if you have had vaccinations within the past 15 days (or 1 year for rabies), as it may impact blood safety."
  },
  {
    question: "Have you avoided surgery or illness within the past 12 months?",
    info: "Surgery or illness within the past year may leave your body in a weakened state, making blood donation unsafe for both you and the recipient.",
    image: "img.png",
    error: "You are not eligible to donate blood if you have had surgery or illness within the past 12 months, as your body may still be recovering."
  },
  {
    question: "Have you avoided pregnancy and breastfeeding in the past 9–12 months?",
    info: "Pregnancy and breastfeeding require additional nutrients, and donating blood during this period may adversely affect the donor's health.",
    image: "img.png",
    error: "You are not eligible to donate blood if you are pregnant or breastfeeding, or have been in the past 9–12 months, to protect your health."
  }
];


let currentQuestionIndex = 0;
const answers = [];

const quizStep = document.getElementById("quiz-step");
const quizQuestion = document.getElementById("quiz-question");
const questionIcon = document.getElementById("question-icon");
const quizInfo = document.getElementById("quiz-info");
const quizContainer = document.getElementById("quiz-container");
const yesBtn = document.getElementById("yes-btn");
const noBtn = document.getElementById("no-btn");
const prevBtn = document.getElementById("prev-btn");
const nextBtn = document.getElementById("next-btn");

function loadQuestion(index) {
  const question = questions[index];
  quizStep.textContent = `Step ${index + 1}/${questions.length}`;
  quizQuestion.textContent = question.question;
  questionIcon.src = question.image;
  quizInfo.textContent = question.info;
  prevBtn.classList.toggle("hidden", index === 0);
  nextBtn.classList.toggle("hidden", index >= answers.length);

  document.querySelectorAll(".quiz-answer-btn").forEach(btn => btn.classList.remove("selected"));
  if (answers[index]) {
    document.getElementById(answers[index]).classList.add("selected");
  }
}

function showFullScreenMessage(type, message) {
  quizContainer.innerHTML = `
    <div class="full-screen-message">
      <h2 class="error_h2" >${type === 'error' ? 'You Are Not Eligible💔' : 'Congratulations!💛'}</h2>
      <p class="error_p">${message}</p>
    </div>`;
}

yesBtn.addEventListener("click", () => {
  answers[currentQuestionIndex] = "yes-btn";
  if (currentQuestionIndex < questions.length - 1) {
    currentQuestionIndex++;
    loadQuestion(currentQuestionIndex);
  } else {
    showFullScreenMessage('success', "You are eligible to donate blood. Thank you for your time!</br>Togather let's Save Life.");
  }
});

noBtn.addEventListener("click", () => {
  answers[currentQuestionIndex] = "no-btn";
  showFullScreenMessage('error', questions[currentQuestionIndex].error);
});

prevBtn.addEventListener("click", () => {
  if (currentQuestionIndex > 0) {
    currentQuestionIndex--;
    loadQuestion(currentQuestionIndex);
  }
});




nextBtn.addEventListener("click", () => {
if (currentQuestionIndex < questions.length - 1) {
currentQuestionIndex++; // Move to the next question step-by-step
loadQuestion(currentQuestionIndex);
}
});

currentQuestionIndex = 0; // Start at the first question
loadQuestion(currentQuestionIndex); // Load the first question

