  var button = document.createElement("button");

  /*Routing*/
  page('/', index);
  page('/category/:categoryName', category);
  page('/post/:postName', postTitle);
  page();
  /*AJAX*/
  function get(url) {
    fetch(url)
      .then((response) => {
        return response.text();
      })
      .then((data) => {
        document.getElementById('main').innerHTML = data
      });
  }

  function post(url, ctx, typ) {

    if (typ = 'category') {
      type = ctx.params.categoryName
    }
    if (typ = 'post') {
      type = ctx.params.postName
    }

    fetch(url, {
        method: "POST",
        body: type
      })
      .then((response) => {
        return response.text();
      })
      .then((data) => {
        document.getElementById('main').innerHTML = data
      });
  }
  /*Views*/
  function index() {
    get('/controllers/node.php')
  }

  function category(ctx) {
    post('/controllers/category.php', ctx, 'category')
    /*Sidebar button*/
    button.innerHTML = "Rozpocznij nowy wątek";
    button.style.cursor = "pointer"
    button.addEventListener("click", newPost);
    document.querySelector('aside').appendChild(button);

    if (window.location.pathname === '/category/Fabularnie') {
      var creator = document.createElement("button");
      creator.innerHTML = "Stwórz nową postać";
      document.querySelector('aside').appendChild(creator);

      var character = document.createElement("button");
      character.innerHTML = "Przypisz swoją postać";
      document.querySelector('aside').appendChild(character);

      eventFabu();
    }
  }

  function postTitle(ctx) {
    post('/controllers/post.php', ctx, 'post')
    /*Sidebar button*/
    button.innerHTML = "Dodaj odpowiedź";
    button.addEventListener("click", newComment);
    document.querySelector('aside').appendChild(button);
  }

  /*Logged user*/

  function newAlliance() {
    uglipop({
      class: 'newform', //styling class for Modal
      source: 'html',
      content: '<form action="controllers/newAlliance.php" method="post"> Podaj nazwę swojego sojuszu <input type="text" name="name"> <br> Z jakiego królestwa/gry pochodzisz ? <input type="text" name="citizien"> <br><textarea name="body" rows="4" cols="40" placeholder="Krótki opis Twojego sojuszu"></textarea><input type="submit"></form>'
    });
  }

  function enterAlliance() {
    uglipop({
      class: 'newform', //styling class for Modal
      source: 'html',
      content: ''
    });
  }

  /*Sidebar*/

  function newPost() {
    uglipop({
      class: 'newform', //styling class for Modal
      source: 'html',
      content: '<form action="/controllers/newpost.php" method="post"><input type="text" name="title" value="Tytuł"><input type="text" name="author" value="Autor"><textarea id="editor" name="text" rows="8" cols="80"></textarea><input type="submit"></form>'
    });
    /*Visual Editor*/
    tail.writer("#editor", {
      locale: "pl",
      markup: "markdown"
    });
  }

  function newComment() {
    uglipop({
      class: 'newform', //styling class for Modal
      source: 'html',
      content: '<form action="/controllers/newcomment.php" method="post"><input type="text" name="author" value="Autor"><textarea id="editor" name="text" rows="8" cols="80"></textarea><input type="submit"></form>'
    });

    /*Visual Editor*/
    tail.writer("#editor", {
      locale: "pl",
      markup: "markdown"
    });
  }

  function creator() {
    uglipop({
      class: 'newform creator', //styling class for Modal
      source: 'html',
      content: '<form action="/controllers/creator.php" method="post"> <input type="text" name="ID" value="Nazwa postaci"> <input type="text" name="imie" value="Imię"> <input type="text" name="nazwisko" value="Nazwisko"> <input type="number" name="wiek" placeholder="Wiek"><br /><br /> <fieldset> Płeć <input type="radio" name="plec" value="Męska">Męska <input type="radio" name="plec" value="Żeńska">Żeńska <input type="radio" name="plec" value="Inna">Inna </fieldset><br />Wygląd <textarea name="wyglad" rows="8" cols="80"></textarea> Charakter <textarea name="charakter" rows="8" cols="80"></textarea> Umiejętności <textarea name="umiejetnosci" rows="8" cols="80"></textarea> Inne <textarea name="inne" rows="8" cols="80"></textarea><button type="submit" name="button">Wyślij</button></form><br /><br />'
    });
  }

  function character() {
    uglipop({
      class: 'newform', //styling class for Modal
      source: 'html',
      content: '<form action="/controllers/character.php" method="post">Tutaj wpisz numer ID dyskusji<input type="number" name="ID"><br />Tutaj podaj swój nick w dyskusji<br /><input type="text" name="owner"><br />A tutaj podaj imię Twojej postaci<input type="text" name="imie"><button type="submit" name="button">Wyślij</button></form>'
    });
  }

  function eventFabu() {
    var button2 = document.querySelector("aside").children[1]
    button2.addEventListener("click", creator);

    var button3 = document.querySelector("aside").children[2]
    button3.addEventListener("click", character);
  }

  function register() {
    document.querySelector("input[name='username']").style.display = "inline-block"
    document.getElementById('login').action = 'controllers/register.php'
  }

  function needReload() {
    setTimeout(function() {
      location.reload();
    }, 100);
  }