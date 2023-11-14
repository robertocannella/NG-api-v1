<?php
session_start();
session_regenerate_id();

exit;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Example API Client</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css">
    <link rel="icon" type="image/png" href="data:image/png;base64,iVBORw0KGgo=">
</head>
<body>

<main class="container">

    <div class="grid">

        <!-- Login and logout -->
        <div>
            <form>
                <label for="username">
                    Username <input name="username" id="username">
                </label>

                <label for="password">
                    Password <input type="password" name="password" id="password">
                </label>

                <button id="login">Log in</button>
            </form>

            <button id="getTasks" style="display: none">Get tasks</button>
            <button id="logout" style="display: none">Log out</button>
        </div>

        <!-- Task list -->
        <div>
            <ul id="tasks"></ul>
        </div>

        <!-- Individual task -->
        <div>
            <dl id="taskDetails" style="display: none">
                <dt>ID</dt>
                <dd id="taskID">&nbsp;</dd>
                <dt>Name</dt>
                <dd id="taskName">&nbsp;</dd>
                <dt>Priority</dt>
                <dd id="taskPriority">&nbsp;</dd>
                <dt>Is completed</dt>
                <dd id="taskIsCompleted">&nbsp;</dd>
            </dl>
        </div>

    </div>
</main>

<script>
    const loginForm = document.forms[0];
    const loginButton = document.getElementById("login");
    const getTasksButton = document.getElementById("getTasks");
    const logoutButton = document.getElementById("logout");
    const list = document.getElementById("tasks");
    const details = document.getElementById("taskDetails");
    const taskID = document.getElementById("taskID");
    const taskName = document.getElementById("taskName");
    const taskPriority = document.getElementById("taskPriority");
    const taskIsCompleted = document.getElementById("taskIsCompleted");

    /**
     * Login
     */
    loginForm.addEventListener('submit', async (e) => {

        e.preventDefault();

        const response = await fetch('https://api.nuvolagraph.com/api/login.php', {
            method: 'POST',
            body: JSON.stringify({
                username: loginForm.username.value,
                password: loginForm.password.value
            })
        });

        const json = await response.text();
        const obj = JSON.parse(json);

        if (response.status == 200) {

            localStorage.setItem("access_token", obj.access_token);
            localStorage.setItem("refresh_token", obj.refresh_token);

            loginForm.style.display = "none";
            logoutButton.style.display = "block";
            getTasksButton.style.display = "block";


        } else {
            alert(obj.message);
        }
    });

    /**
     * Logout
     */
    logoutButton.addEventListener('click', async (e) => {

        e.preventDefault();

        logoutButton.style.display = "none";
        getTasksButton.style.display = "none";
        details.style.display = "none";

        const response = await fetch('https://api.nuvolagraph.com/api/logout.php', {
            method: 'POST',
            body: JSON.stringify({
                token: localStorage.getItem("refresh_token")
            })
        });

        localStorage.removeItem("access_token");
        localStorage.removeItem("refresh_token");

        list.style.display = "none";
        loginForm.style.display = "block";
    });

    /**
     * Get tasks
     */
    getTasksButton.addEventListener('click', async (e) => {

        e.preventDefault();

        const response = await fetch("https://api.nuvolagraph.com/api/tasks", {
            headers: {
                "Authorization": "Bearer " + localStorage.getItem("access_token")
            }
        });

        const json = await response.text();
        const obj = JSON.parse(json);

        if (response.status == 200) {

            list.innerHTML = '';

            for (const task of obj) {

                const anchor = document.createElement("a");
                const li = document.createElement("li");

                anchor.classList.add('taskLink');
                anchor.textContent= task.name;
                anchor.setAttribute('data-id', task.id);

                li.appendChild(anchor);
                list.appendChild(li);
            }
        }

        list.style.display = "block";
        details.style.display = "block";
        getTasksButton.style.display = "none";
    });

    /**
     * Get individual task
     */
    list.addEventListener('click', async (e) => {

        e.preventDefault();

        const id = e.target.getAttribute("data-id");

        if (id) {

            const response = await fetch(`https://api.nuvolagraph.com/api/tasks/${id}`, {
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem("access_token")
                }
            });

            const json = await response.text();
            const obj = JSON.parse(json);

            if (response.status == 200) {

                taskID.innerHTML = obj.id + "&nbsp;";
                taskName.innerHTML = obj.name + "&nbsp;";
                taskPriority.innerHTML = obj.priority + "&nbsp;";
                taskIsCompleted.innerHTML = obj.is_completed + "&nbsp;";

            } else {

                console.log("Access token expired, requesting new one");

                const response = await fetch('https://api.nuvolagraph.com/api/refresh.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        token: localStorage.getItem("refresh_token")
                    })
                });

                const json = await response.text();
                const obj = JSON.parse(json);

                if (response.status == 200) {

                    console.log("Got new access token and refresh token");

                    localStorage.setItem("access_token", obj.access_token);
                    localStorage.setItem("refresh_token", obj.refresh_token);
                }
            }
        }
    });
</script>

</body>
</html>
