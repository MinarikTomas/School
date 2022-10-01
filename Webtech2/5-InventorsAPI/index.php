
<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Zadanie 5</title>
</head>
<body>
<header>
    <h1>Zadanie 5</h1>
</header>
<form id="send-form">
    <div>
        <select name="method" id="method">
            <option value="get">GET</option>
            <option value="post">POST</option>
            <option value="delete">DELETE</option>
            <option value="put">UPDATE</option>
        </select>
        <input type="text" name="url"  id="url" placeholder="/inventors/10">
        <input type="button" id="send" value="Send">
    </div>
    <div>
        <label for="req-body">Request body</label><br>
        <textarea id="req-body" rows="1" name="body" disabled>
        </textarea>
    </div>
</form>
<div>
    <h4>Možnosti</h4>
    <pre>
get /rok
get /inventors/ , get /inventors/id , get /inventors/priezvisko
get /inventions/ , get /inventions/storočie
post /inventors/ - death_date, death_place, year sú nepovinné inventions môže byť prázdne pole
post /inventions/ - year je nepovinný
delete /inventors/id
update /inventors/id
dátum musí byť vo formáte d.m.y
    </pre>
</div>
<div>
    <h4>Response</h4>
</div>
<div>
    <span id="response-status"></span>
</div>
<div>
    <pre id="response"></pre>
</div>

<script>
    const form = document.querySelector('#send-form');
    const button = document.querySelector('#send');
    const responseStatus = document.querySelector('#response-status');
    const responseBody = document.querySelector('#response');
    const select = document.querySelector('#method');
    const textarea = document.querySelector('#req-body');
    const input = document.querySelector('#url');

    input.addEventListener('input', () => {
        displayRequestBody();
    })

    select.addEventListener('change', () => {
        displayRequestBody();
    })

    const displayRequestBody = () => {
        if(select.value === 'delete' || select.value === 'get'){
            disableRequestBody();
        }else{
            displayRequestJson();
        }
    }

    const disableRequestBody = () => {
        textarea.value = "";
        textarea.disabled = true;
        textarea.rows = 1;
    }

    const displayRequestJson = () => {
        const entity = input.value.split('/')[1];
        switch (select.value){
            case 'post':
                if(entity && entity.localeCompare('inventions') === 0){
                    displayRequestBodyInventionsPost();
                }else if(entity && entity.localeCompare('inventors') === 0){
                   displayRequestBodyInventorsPost();
                }else{
                    disableRequestBody();
                }
                break;
            case 'put':
                if(entity && entity.localeCompare('inventors') === 0) {
                    displayRequestBodyInventorsPut();
                }else{
                    disableRequestBody();
                }
                break;
        }
    }

    const displayRequestBodyInventionsPost = () => {
        textarea.value = `{
    "inventor_id": "",
    "year": "",
    "description": ""
}`;
        textarea.disabled = false;
        textarea.rows = 6;
    }

    const displayRequestBodyInventorsPost = () => {
        textarea.value = `{
    "name": "",
    "surname": "",
    "birth_date": "",
    "birth_place": "",
    "description": "",
    "death_date": "",
    "death_place": "",
    "inventions": [
        {
            "year": "",
            "description": ""
        }
    ]
}`;
        textarea.disabled = false;
        textarea.rows = 16;
    }

    const displayRequestBodyInventorsPut = () => {
        textarea.value = `{
    "name": "",
    "surname": "",
    "birth_date": "",
    "birth_place": "",
    "description": "",
    "death_date": "",
    "death_place": ""
}`;
        textarea.disabled = false;
        textarea.rows = 10;
    }

    button.addEventListener('click', () => {
        responseBody.innerHTML = "";
        responseStatus.innerHTML = "";
        const data = new FormData(form);
        const url = data.get('url');
        let body = null;
        if(data.get('method') === 'post' || data.get('method') === 'put'){
            body = JSON.stringify(JSON.parse(data.get('body')));
        }
        if(url.charAt(0) === '/'){
            fetch('https://site132.webte.fei.stuba.sk/zadanie5qwe' + data.get('url'),
                {method: String(data.get('method')),
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: body}
            ).then(response => {
                responseStatus.innerHTML = response.status + response.statusText;
                if(response.status === 200 || response.status === 201){
                    response.json()
                        .then(data => {
                            responseBody.textContent = JSON.stringify(data, undefined, 2);
                        })
                }
            })
        }
    })
</script>
</body>
</html>
