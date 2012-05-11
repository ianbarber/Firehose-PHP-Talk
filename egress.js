var app = require('http').createServer(handler), 
  io = require('socket.io').listen(app), 
  fs = require('fs'),
  zmq = require('zmq'),
  sock = zmq.socket('sub');

app.listen(8080);
sock.subscribe('');
sock.bind('tcp://*:5566');

sock.on('message', function (msg) {
    var data = JSON.parse(msg);
	io.sockets.emit("position", event);
});

function handler (req, res) {
  fs.readFile(__dirname + '/viewer.html',
  function (err, data) {
    if (err) {
      res.writeHead(500);
      return res.end('Error loading viewer.html');
    }

    res.writeHead(200);
    res.end(data);
  });
}