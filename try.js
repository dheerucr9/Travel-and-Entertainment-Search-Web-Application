var express = require('express')
var bodyParser = require('body-parser')
var request = require('request')
var app = express()

app.use(bodyParser.urlencoded({extended:false}));
app.use(bodyParser.json());
app.use(function(req, res, next) {
    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
    next();
});


app.get('/',function(req,res){
  var nextpageURL = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?pagetoken=CrQCJQEAAAXcDLtKW7V98sYHxb8p4jOH8p_LxDZUI2_NXOrxV4pdaY0BGAy1t7yHCom3TzGg0PYqXb3FxaSoObbwYssLxQ2CWSpJmrJWldnOsGvIGBraBd8bpQqd3fqIaqzVbOuVYno3qD-s9sn63GWs0jY7FyL0XX2pkPj_9dCyaQmjCoFGN_Iq3lXT5EXg3Phk7paFYMBfhGKSE3t0MG603AsDVE7vO8aZ-i267Jt8qaVQfKdJ8KfoLlCcl3xr2RiWG-xCMCT6z_VFrynBYt41vOJlgQwI6yvwQev1lDe929pq-0WCGubiBsUoM6RhA0PqO2A2GpbcKA_8B3HVYsSFn1Wf0VIQHFfCcXX42B8zQt9NsrcmyHbA2xyworsTr_nAxHdmZG_01dxKBl1r4IU022MQrm4SEJNCeKimzWdvUaKt2YPYLb8aFMNDCG2Dcf5ILNWElGQaeEr91TpC&key=AIzaSyB0mO46_Gm8eZN2-HKbl2JAp27bLKKFV3o";
  request(nextpageURL, function (error, response, body) {
    var nextdata = JSON.parse(body);
    //console.log(response);
    console.log(body);
    //console.log(nextdata);
    //res.send(nextdata);
    });
});

app.listen(3000, () => console.log('Example app listening on port 3000!'))
