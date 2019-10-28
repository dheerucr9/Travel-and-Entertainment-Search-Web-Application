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


app.get('/getYelp',function(req,res){
  var nextpageURL = "https://api.yelp.com/v3/businesses/search?term=" + req.query.name.split(" ").join("+") + "&latitude=" +req.query.lat + "&longitude=" + req.query.long + "&limit=1";
  //console.log(nextpageURL);
  var options = {
  url: nextpageURL,
  headers: {
    Authorization:"Bearer *******"
  }
};

function callback(error, response, body) {
  if (!error && response.statusCode == 200) {
    var info = JSON.parse(body);
    if(info.businesses.length!=0){
    //res.send(info)
    var nextpageURL2 = "https://api.yelp.com/v3/businesses/" + info.businesses[0]["id"] + "/reviews";
    //console.log(nextpageURL);
    var options2 = {
    url: nextpageURL2,
    headers: {
      Authorization:"Bearer ******"
    }
  };

  function callback2(error, response, body) {
    if (!error && response.statusCode == 200) {
      var info2 = JSON.parse(body);
      res.send(info2)
}
}

      //console.log();
    }
    else{
      res.send({});
    }
    //console.log(info.stargazers_count + " Stars");
    //console.log(info.forks_count + " Forks");
    request(options2, callback2);
  }
}

request(options, callback);
});


app.get('/nextPage',function(req,res){
  var nextpageid = req.query.token;
  var nextpageURL = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?pagetoken=" + nextpageid + "&key=*****";
  console.log(nextpageURL);
  request(nextpageURL, function (error, response, body) {
    var nextdata = JSON.parse(body);


    //console.log(body);
    //console.log(nextdata);
    res.send(nextdata);
    });
});

app.get('/getLatLong',function(req,res){
  var url = "https://maps.googleapis.com/maps/api/geocode/json?address=";
  url+= req.query.token.split(" ").join("+");
  url+="&key=*****";
  //console.log(url);

  request(url, function (error, response, body) {
  data = JSON.parse(body);
  var latlong = data['results'][0]['geometry']['location']; // Print the HTML for the Google homepage.
  lat = latlong['lat'];
  long = latlong['lng'];
  res.send(lat.toString()+","+long.toString());
});
});

app.get('/', function(req, res){
  var lat,long;
  //console.log(req.query);
  console.log(req);
  var mURL = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?";
  //res.send("user");
  if(req.query.dist.substring(0,5)=="here+"){
    var latlong = req.query.dist.substring(5,req.query.dist.length).split(",");
    lat = latlong[0];
    long = latlong[1];
    //console.log(lat+","+long);
    mURL+="location=" + lat + "," + long;
    rad_fl = parseFloat(req.query.rad) * 1609.34;
    mURL+="&radius=" + rad_fl.toString();
    mURL+="&type=" + req.query.cat;
    mURL+= "&keyword=" + req.query.key.split(" ").join("+") + "&key=*****";
    console.log(mURL);
    request(mURL, function (error, response, body) {
      data = JSON.parse(body);
      //console.log(data);
      res.send(data);
      //console.log(data);
      //var latlong = data['results'][0]['geometry']['location']; // Print the HTML for the Google homepage.
      //lat = latlong['lat'];
      //long = latlong['lng'];
    });

  }else{
    var url = "https://maps.googleapis.com/maps/api/geocode/json?address=";
    url+= req.query.dist.split(" ").join("+");
    url+="&key=*****";
    //console.log(url);

request(url, function (error, response, body) {
  data = JSON.parse(body);
  var latlong = data['results'][0]['geometry']['location']; // Print the HTML for the Google homepage.
  lat = latlong['lat'];
  long = latlong['lng'];

  console.log(321);
  mURL+="location=" + lat + "," + long;
  rad_fl = parseFloat(req.query.rad) * 1609.34;
  mURL+="&radius=" + rad_fl.toString();
  mURL+="&type=" + req.query.cat;
  mURL+= "&keyword=" + req.query.key.split(" ").join("+") + "&key=*****";
  console.log(mURL);
  request(mURL, function (error, response, body) {
    data = JSON.parse(body);
    //console.log(data);
    data["foundLat"] = lat;
    data["foundLong"] = long;
    //console.log(data);
    res.send(data);
    //console.log(data);
    //var latlong = data['results'][0]['geometry']['location']; // Print the HTML for the Google homepage.
    //lat = latlong['lat'];
    //long = latlong['lng'];
  });

});
  }

});



//app.listen(8081, () => console.log('Example app listening on port 8081!'))

var port = process.env.PORT || 8081;
app.listen(port, function() {
console.log("Server started on the port 8081");
});
