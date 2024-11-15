const prevButton = document.querySelector('.prew');
const nextButton = document.querySelector('.next');
const slides = document.querySelectorAll('.sale__slid');

// Найдем активные элементы
let activeOne = document.querySelector('.activeOne');
let activeTwo = document.querySelector('.activeTwo');
let activeLast = document.querySelector('.activeLast');

// Функция для удаления классов активности
function removeActiveClasses() {
  activeOne.classList.remove('activeOne');
  activeTwo.classList.remove('activeTwo');
  activeLast.classList.remove('activeLast');
}

// Функция для добавления классов активности
function addActiveClasses(newActiveOne, newActiveTwo, newActiveLast) {
  newActiveOne.classList.add('activeOne');
  newActiveTwo.classList.add('activeTwo');
  newActiveLast.classList.add('activeLast');
}

// Логика для кнопки "вперед"
nextButton.addEventListener('click', () => {
  // Индексы активных элементов
  let indexOne = Array.from(slides).indexOf(activeOne);
  let indexTwo = Array.from(slides).indexOf(activeTwo);
  let indexLast = Array.from(slides).indexOf(activeLast);

  // Удаляем текущие активные классы
  removeActiveClasses();

  // Рассчитываем новые индексы с проверкой на границы массива
  indexOne = (indexOne + 1) % slides.length;
  indexTwo = (indexTwo + 1) % slides.length;
  indexLast = (indexLast + 1) % slides.length;

  // Добавляем новые активные классы
  addActiveClasses(slides[indexOne], slides[indexTwo], slides[indexLast]);

  // Обновляем активные элементы
  activeOne = slides[indexOne];
  activeTwo = slides[indexTwo];
  activeLast = slides[indexLast];
});

// Логика для кнопки "назад"
prevButton.addEventListener('click', () => {
  // Индексы активных элементов
  let indexOne = Array.from(slides).indexOf(activeOne);
  let indexTwo = Array.from(slides).indexOf(activeTwo);
  let indexLast = Array.from(slides).indexOf(activeLast);

  // Удаляем текущие активные классы
  removeActiveClasses();

  // Рассчитываем новые индексы с проверкой на границы массива
  indexOne = (indexOne - 1 + slides.length) % slides.length;
  indexTwo = (indexTwo - 1 + slides.length) % slides.length;
  indexLast = (indexLast - 1 + slides.length) % slides.length;

    // Добавляем новые активные классы
    addActiveClasses(slides[indexOne], slides[indexTwo], slides[indexLast]);

    // Обновляем активные элементы
    activeOne = slides[indexOne];
    activeTwo = slides[indexTwo];
    activeLast = slides[indexLast];
  });


let modalReg = document.getElementById("modalReg");
let modalAuto = document.getElementById("modalAuto");
let btnOpenAuto = document.getElementById("openModal");
let btnAuto = document.getElementById("autoSubmitBtn");
let btnReg = document.getElementById("registrationSubmitBtn");
let closeReg = document.getElementById("closeReg");
let closeAuto = document.getElementById("closeAuto");
let openAutoMod = document.getElementById("openAuto");
let openReg = document.getElementById("openReg");


// Функции  открытия модального окна
btnOpenAuto.onclick = function() {
    modalAuto.style.display = "block";
}

openAutoMod.onclick = function() {
    modalAuto.style.display = "block";
    modalReg.style.display = "none";
}

openReg.onclick = function() {
    modalReg.style.display = "block";
    modalAuto.style.display = "none";
}

// Функция закрытия модального окна регистрации
closeReg.onclick = function() {
    modalReg.style.display = "none";
}

closeAuto.onclick = function() {
    modalAuto.style.display = "none";
}

// Функция закрытия модального окна при клике на фон
window.onclick = function(event) {
    if (event.target == modalReg) {
        modalReg.style.display = "none";
    } else if (event.target == modalAuto) {
        modalAuto.style.display = "none";
    }
}

//Добавить обработчики на данные кнопки для выполнения действий
btnAuto.onclick = function() {
    modalAuto.style.display = "none";
    window.location.href = '/personaccount/';
}
btnReg.onclick = function() {
    modalReg.style.display = "none";
}