const WebSocket = require('ws');
const https = require('https');
const fs = require('fs');

const server = https.createServer({
    cert: fs.readFileSync("/home/***"),
    key: fs.readFileSync("/home/***")
});

server.listen(9000);

const ws = new WebSocket.Server( { server} );

let players = 0;
const board = [];
let interval;
let running = false;

const createBoard = () => {
    for(let i = 0; i < 25; i++){
        board[i] = [];
        for(let j = 0; j < 25; j++){
            board[i][j] = 0;
        }
    }
}

const setPlayer1 = (socket) => {
    socket.name = 'player 1';
    socket.pos = [2, 2];
    socket.dir = [1, 0];
    socket.id = 1;
    socket.dead = false;
}

const setPlayer2 = (socket) => {
    socket.name = 'player 2';
    socket.pos = [22, 22];
    socket.dir = [-1, 0];
    socket.id = 2
    socket.dead = false;
}

const getPlayers = (socket) => {
    if(players === 0){
        setPlayer1(socket);
        socket.send(JSON.stringify({"id": 1}));
    }else if(players === 1){
        setPlayer2(socket);
        socket.send(JSON.stringify({"id": 2}));
    }else{
        socket.send(JSON.stringify({"result": "Game is already running"}));
    }
    players++;
}

const isOutOfBorder = (socket) => {
    return socket.pos[0] < 0 || socket.pos[0] > 24 || socket.pos[1] < 0 || socket.pos[1] > 24;

}

const isOccupied = (socket) => {
    return board[socket.pos[0]][socket.pos[1]] !== 0;

}

const setNewPos = (socket) => {
    socket.pos[0] += socket.dir[0];
    socket.pos[1] += socket.dir[1];
    if(isOutOfBorder(socket) || isOccupied(socket)) {
        socket.dead = true;
        clearInterval(interval);
    }else{
        board[socket.pos[0]][socket.pos[1]] = socket.id;
    }
}

const getPlayersStatus = () => {
    const status = []
    ws.clients.forEach(client => {
        if(client.id){
            status.push(client.dead);
        }
    })
    return status;
}

const getGameStatus = () => {
    const status = getPlayersStatus();
    if(status[0] && status[1]){
        return 'Draw';
    }else if(status[0] && !status[1]){
        return 'Player 2 won';
    }else if(!status[0] && status[1]){
        return 'Player 1 won';
    }
    return null;
}

const sendResult = (result) => {
    ws.clients.forEach(client => {
        if(client.id){
            client.send(JSON.stringify({"result": result}));
        }
    })
}

const resetPlayers = () => {
    ws.clients.forEach(client => {
        if(client.id === 1){
            setPlayer1(client);
        }else if(client.id === 2){
            setPlayer2(client);
        }
    })
}

const startGame = () => {
    running = true;
    interval = setInterval(() => {
        ws.clients.forEach(client => {
            if(client.id) {
                setNewPos(client);
                client.send(JSON.stringify({"board": board, "dir": client.dir}));
            }
        })
        const status = getGameStatus();
        if (status){
            running = false;
            sendResult(status);
        }
    }, 300);
}

const restartGame = () => {
    createBoard();
    resetPlayers();
    if (!running) {
        startGame();
    }
}

ws.on('connection', (socket) => {
    getPlayers(socket);
    if (!running && players === 2){
        createBoard();
        startGame();
    }
    socket.on('message', (data) => {
        if (data.toString() === 'restart' && socket.id) {
            restartGame();
        } else if(data.toString() === 'end'){
            clearInterval(interval);
            running = false;
            players = 0;
        }else if(socket.id){
            const dir = data.toString().split(',');
            socket.dir[0] = Number(dir[0]);
            socket.dir[1] = Number(dir[1]);
        }
    })
});