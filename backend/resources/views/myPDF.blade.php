<!DOCTYPE html>
<html lang="sk">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <title>My PDF</title>
    <style>

        .bg-light {
        --bs-bg-opacity: 0;
        background-color: rgba(var(--bs-light-rgb),var(--bs-bg-opacity)) !important;
        }
        .b-settings{
        font-weight: 500;
        color: black;
        }

        .bigger-b{
            font-size: 20px;
            font-weight: 550;
        }

        .v-container{
            width: 100%;
            padding: 16px;
            margin-right: auto;
            margin-left: auto;
        }

        .box-shadow {
            box-shadow: 0px 2px 1px -1px var(--v-shadow-key-umbra-opacity, rgba(0, 0, 0, 0.2)), 0px 1px 1px 0px var(--v-shadow-key-penumbra-opacity, rgba(0, 0, 0, 0.14)), 0px 1px 3px 0px var(--v-shadow-key-penumbra-opacity, rgba(0, 0, 0, 0.12));
        }

        .button-color{
            color: #1867C0 !important;
        }

        body{
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid mb-5">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <i class="navbar-brand" > </i>
                <b class = "bigger-b me-5">Finálne zadanie</b>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#"><b class = "b-settings">Tutorial</b></a>
                    </li>
                </ul>
            <form class="d-flex">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" id="lan" onclick="changeLanguage()" href="#">SK</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="redirectToLogin()" href="#"><b class = "b-settings">LOGIN</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="redirectToRegister()" href="#"><b class = "b-settings">REGISTER</b></a>
                    </li>
                </ul>
            </form>
            </div>
        </div>
    </nav>

    <div class = "container container-md">
        <div id="skBody">
        <div class = "text-center">
            <h1>Návod na používanie aplikácie</h1>
        </div>
        <div class = "box-shadow rounded">
            <div class = "p-5">
                <p class="fw-normal">Táto aplikácia slúži na zadávanie súborov s matematickými úlohami, ich generovanie, zadávanie riešení týchto úloh a následnú kontrolu správnosti zadaných riešení.</p>
                <p class="fw-normal">Na úvodnej stránke je možné vidieť formulár na prihlásenie. Ak ešte nemáte vytvorený účet, je možné sa zaregistrovať (vpravo hore <b>REGISTRÁCIA</b>). V pravej hornej časti je taktiež možnosť prepínať jazyky (slovenčina/angličtina).</p>
                <p class="fw-normal">Každý prihlásený používateľ ma taktiež možnosť sa odhlásiť tlačidlom vpravo hore <b>ODHLÁSIŤ</b>. Následne je používateľ presmerovaný na hlavnú stránku s možnosťou prihlásenia sa.</p>
                <p class="fw-normal">V našej aplikácii sú dve role a to študent a učiteľ. Od toho, akú rolu predstavuje používateľ, závisí, čo sa zobrazí po prihlásení.</p>
                <h5 class = "mt-5 fw-bold">Prihlásený učiteľ</h5><br>
                <p class="fw-normal">V prípade, že je prihlásený učiteľ, zobrazí sa mu stránka špecifická len pre neho.</p>
                <p class="fw-normal">V hornej časti sa zobrazuje tabuľka so študentmi (ID, krstné meno, priezvisko a e-mail), ktorú je možné zotriediť a taktiež aj exportovať do CSV súboru. </p>
                <p class="fw-normal">V ďalšej časti má možnosť vložiť LaTeX text, v ktorom sa nachádza sada príkladov. Následne zadá názov a potvrdí. </p>
                <p class="fw-normal">V ďalšej časti je možnosť vytvoriť sadu príkladov. Učiteľ bude mať možnosť definovať, z ktorých latexových súborov si bude môcť študent generovať príklady na riešenie. Zadá sa názov a maximálny počet bodov za úlohu. Taktiež má možnosť určiť, v akom období je možné z konkrétnej sady generovať príklady.   Ak dátum nebude určený, tak generovanie príkladov z tejto sady je otvorené. Následne je potrebné stlačiť tlačidlo potvrdiť.</p>
                <h5 class = "mt-5 fw-bold">Prihlásený študent</h5><br>
                <p class="fw-normal">V prípade, že je prihlásený študent, zobrazí sa mu stránka špecifická len pre neho.</p>
                <p class="fw-normal">Na tejto stránke má študent možnosť vidieť aktuálne a minulé zadania. Pri každom zadaní vidí názov zadania, čas, do kedy je možné toto zadanie vypracovať, čas, ktorý mu zostáva, meno učiteľa, počet úloh a počet bodov. Po kliknutí na konkrétne zadanie je možné vidieť názov sady a po stlačení tlačidla GENEROVAŤ si môže vygenerovať úlohy. V prípade, že študent ešte nevyriešil príklad, je možné napísať odpoveď a odoslať. Na zápis odpovede môže použiť matematický editor, ktorý je možné zobraziť po stlačení tlačidla na konci riadku na zadávanie odpovedí. Následne môže vidieť, koľko bodov za danú úlohu získal. </p>

                <a onclick="redirectToDownload()" target="_blank" class="btn button-color"><b>PDF</b></a>
            </div>
        </div>
        </div>
        <div id="enBody">
            <div class="text-center">
                <h1>User Manual for Using the Application</h1>
            </div>
            <div class="box-shadow rounded">
                <div class="p-5">
                    <p class="fw-normal">This application is used for entering mathematical problem files, generating them, entering solutions to these problems, and checking the correctness of the entered solutions.</p>
                    <p class="fw-normal">On the home page, you can see a login form. If you don't have an account yet, you can register (top right <b>REGISTER</b>). There is also an option to switch languages (Slovak/English) in the upper right corner.</p>
                    <p class="fw-normal">Each logged-in user also has the option to log out by clicking the button in the upper right corner <b>LOGOUT</b>. Afterwards, the user will be redirected to the main page with the option to log in.</p>
                    <p class="fw-normal">In our application, there are two roles: student and teacher. Depending on the user's role, different content will be displayed after logging in.</p>
                    <h5 class="mt-5 fw-bold">Logged-in Teacher</h5><br>
                    <p class="fw-normal">If a teacher is logged in, they will see a page specific to them.</p>
                    <p class="fw-normal">At the top, a table with students (ID, first name, last name, and email) is displayed, which can be sorted and exported to a CSV file.</p>
                    <p class="fw-normal">In the next section, the teacher can enter LaTeX text that contains a set of exercises. They need to enter a title and confirm.</p>
                    <p class="fw-normal">In the following section, there is an option to create a set of exercises. The teacher can define from which LaTeX files the students will generate exercises for solving. They need to enter a title, the maximum number of points for the task, and the period during which the specific set can generate exercises. If no date is specified, generating exercises from this set is open. Finally, they need to press the confirm button.</p>
                    <h5 class="mt-5 fw-bold">Logged-in Student</h5><br>
                    <p class="fw-normal">If a student is logged in, they will see a page specific to them.</p>
                    <p class="fw-normal">On this page, the student can view current and past assignments. For each assignment, they can see the assignment title, deadline, time remaining, teacher's name, number of tasks, and number of points. By clicking on a specific assignment, they can see the set title, and by pressing the GENERATE button, they can generate tasks. If the student has not solved the exercise yet, they can write an answer and submit it. They can use a mathematical editor to write the answer, which can be displayed by clicking the button at the end of the answer input row. Afterwards, they can see how many points they earned for that task.</p>

                    <a onclick="redirectToDownload()" target="_blank" class="btn button-color"><b>PDF</b></a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

    <script>
        function redirectToLogin() {
            window.location.href = '{{ env('FE_URL') }}/login';
        }

        function redirectToRegister() {
            window.location.href = '{{ env('FE_URL') }}/register';
        }

        function redirectToDownload() {
            window.location.href = '{{ env('APP_URL') }}/api/generate-pdf';
        }

        var lang = "SK";
        document.getElementById("enBody").style.display = "none";
        function changeLanguage() {
            var langBtn = document.getElementById("lan");
            if (lang == "SK") {
                langBtn.innerHTML = "SK";
                document.getElementById("enBody").style.display = "none";
                document.getElementById("skBody").style.display = "block";
                lang = "EN";
            } else {
                langBtn.innerHTML = "EN";
                document.getElementById("enBody").style.display = "block";
                document.getElementById("skBody").style.display = "none";
                lang = "SK";
            }
        }
    </script>
