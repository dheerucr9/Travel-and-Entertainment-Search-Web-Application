<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <style>
            form{
                border: 4px;
                border-style: solid;
                border-color: lightgray;
                margin-left: 80px;
                margin-right: 80px;
            }
            
            form h1{
                margin-top: 10px;
                text-align: center;
                margin-bottom: 5px;
            }
            
            form hr{
                margin-left: 5px;
                margin-right: 5px;
                color: lightgray;
            }
            form b{
                   margin-left: 5px;
            }
            #search{
                margin-bottom: 20px;
            }
            #loc{
                margin-left: 327px;
            }
            table{
                border-collapse: collapse;
                width: 90%;
            }
            .small img{
                width: 35px;
                height: 25px;
            }
            
            .smallimg{
                width: 35px;
                height: 25px;
            }
            td{
                font-size: 15px;
                padding-left: 15px;
            }
            #printHere{
                margin-left: 75px;
            }
            #no{
                text-align: center;
                width: 100%;
                background: lightgray;
            }
            #heading{
                text-align: center;
                font-weight: bold;
            }
            .tabcent{
                width: auto;
    margin-left: auto;
    margin-right: 80px;
            }
            .tabcent1{
                width: auto;
    margin-left: 80px;
    margin-right: 80px;
            }
            
            .cent{
                text-align: center;
            }
        </style>
        
        
        <script>
            <?php 
                $key = "";
                $cat = "default";
                $dist = "-1";
                $from = "";
                $loc = "";
                $flag = "0";
                $lat = "0";
                $long = "0";
                $result = json_encode(json_decode("{}"));
                $rev = "";
                $table = "";
                $mapFlag = "-1";
                $link = "";
                $contextOptions=array( "ssl"=>array( "verify_peer"=>false, "verify_peer_name"=>false) ); 
            
            
                if(isset($_POST['link'])){
                        echo 'console.log(999999);';
                        $link=$_POST["link"] . "&key=" . $_POST["key"];
                        //echo $link;
                        $rev = file_get_contents($link, false, stream_context_create($contextOptions));
                        
                        echo $rev;
                        $revJson = json_decode($rev, true);
                 //   https://maps.googleapis.com/maps/api/place/photo?maxwidth=750&photoreferen
                    //ce=CnRtAAAATLZNl354RwP_9UKbQ_5Psy40texXePv4oAlgP4qNEkdIrkyse7rPXYGd9D_Uj1r
//VsQdWT4oRz4QrYAJNpFX7rzqqMlZw2h2E2y5IKMUZ7ouD_SlcHxYq1yL4KbKUv3qtWgTK0A6Qb
//Gh87GB3sscrHRIQiG2RrmU_jF4tENr9wGS_YxoUSSDrYjWmrNfeEHSGSc3FyhNLlBU&key=YOU
//R_API_KEY
                        //echo gettype($revJson);
                        if(in_array('photos',array_keys($revJson['result']))){
                            $temp = min(count($revJson['result']['photos']),5);
                        }
                        else{
                            $temp = 0;
                        }
                        echo $temp;
                        for( $i = 0; $i < $temp; $i++){
                            $url = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=" . $revJson['result']['photos'][$i]['width'] . "&photoreference=" . $revJson['result']['photos'][$i]['photo_reference'] . "&key=AIzaSyB0mO46_Gm8eZN2-HKbl2JAp27bLKKFV3o";
                            file_put_contents('image' . $i . ".jpg", file_get_contents($url,false,stream_context_create($contextOptions)));
                            
                        }    
                    
                        exit;
                        //echo 'console.log($_POST["link"]);';
                    
                        
                        //echo 'console.log(' . $asdf . ');';
                    }
            
            
                if(isset($_POST['submit'])){
                    
                    echo 'console.log(765467);';
                    
                    $mURL = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?";
                    
                    if(isset($_POST['from'])){
                        if(substr($_POST['from'],0,5)=="here+"){
                            $flag = "0";
                            $loc = substr($_POST['from'],5);
                            $temp = explode("&", $loc);
                            $lat = $temp[0];
                            $long = $temp[1];
                        }
                        else{
                            $flag = "1";
                            $loc = $_POST['from'];
                            $url="https://maps.googleapis.com/maps/api/geocode/json?address=";
                            $temp = str_replace(" ","+",$loc);
                            $temp = str_replace(",","",$temp);
                            $url= $url . $temp . "&key=" . "AIzaSyB0mO46_Gm8eZN2-HKbl2JAp27bLKKFV3o";

                            
                            $op=file_get_contents($url, false, stream_context_create($contextOptions));
                            $json = json_decode($op, true);
                            $lat = $json['results'][0]['geometry']['location']['lat'];
                            $long = $json['results'][0]['geometry']['location']['lng'];
                        }
                        $mURL = $mURL . "location=" . $lat . "," . $long;
                    }
                    
                    if(isset($_POST['dist'])){
                        $dist = $_POST['dist'];
                        if($dist == ""){
                            $mURL = $mURL . "&radius=16093.4";
                            $dist = "-1";
                        }
                        else{
                            $mile = floatval($dist) * 1609.34;
                            
                            $mURL = $mURL . "&radius=" . strval($mile);
                        }
                    }
                    else{
                        echo 'console.log("this");';
                    }
                    
                    if(isset($_POST['category'])){
                        $cat = $_POST['category'];
                        $mURL = $mURL . "&type=" . $cat;
                    }
                    

                    if(isset($_POST['keyword'])){
                        $key = $_POST['keyword'];
                        $m = explode(" ", $key);
                        $M = $m[0];
                        for($x = 1; $x < count($m); $x++){
                            $M = $M . $m[$x];
                        }
                        $mURL = $mURL . "&keyword=" . $M . "&key=AIzaSyB0mO46_Gm8eZN2-HKbl2JAp27bLKKFV3o";
                    }
                                        
                    //echo 'console.log("' . $mURL . '");';
                    $result=json_encode(file_get_contents($mURL, false, stream_context_create($contextOptions)),true);
                    //echo 'console.log("' . $result["results"][0]["geometry"]["location"]["lat"] . '");';
                    
                    if(isset($_POST["table"])){
                        $table = $_POST["table"];
                            //echo 'console.log(' . $_POST["table"] .');';
                    }
                    
                    if(isset($_POST["clickedLink"])){
                        //$table = $_POST["table"];
                            //echo 'console.log(' . $_POST["clickedLink"] .');';
                        echo 'console.log(9191);';
                    }
                    

                }
    ?>        
        </script>
        
    
        
        
        
        <script type="text/javascript">
            var lat = 0;
            var lon = 0;
            var mapScript = 0;
            var mapFlag = "div";
            var gmarkers = [];
            var jsonObj = {};
            var X = {};
            function getLoc() {
                
                document.getElementById("key").value="<?php echo $key; ?>";
                document.getElementById("cat").value="<?php echo $cat; ?>";
                document.getElementById("printHere").value="<?php echo $table?>";
                if ( "<?php echo $dist; ?>" != "-1"){
                    document.getElementById("dist").value="<?php echo $dist; ?>"
                }
                if ("<?php echo $flag?>" == "0"){
                    document.getElementById("here").checked=true;
                    document.getElementById("loc").checked=false;
                    document.getElementById("here").value="<?php echo $loc; ?>";
                }else{
                    document.getElementById("here").checked=false;
                    document.getElementById("loc").checked=true;
                    document.getElementById("location").value="<?php echo $loc; ?>";
                    document.getElementById("location").disabled=false;
                }
                xmlhttp=new XMLHttpRequest();
                
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("search").disabled = false;
                        var data= JSON.parse(xmlhttp.responseText);
                        lat = JSON.stringify(data["lat"]);
                        lon = JSON.stringify(data["lon"]);
                        document.getElementById("here").value="here+"+lat+"&"+lon;
                    }
                };
                
                xmlhttp.open("GET","http://ip-api.com/json",true); 
                xmlhttp.send();
                
                /**/
                var x = <?php echo $result; ?>;
                //console.log(Object.keys(x).length);
                if(x.length != undefined){
                    if(x.length==81){
                        document.getElementById("printHere").innerHTML="<div id='no'>No Records has been found</div>";
                    }
                    else{
                        var txt = "";
                        X = JSON.parse(x);
                        
                        txt+="<table class='small' border=1><tr><th>Category</th><th>Name</th><th>Address</th></tr>";
                        //print table
                        for(var i = 0; i < X.results.length;i++){
                            txt+= "<tr><td><img src='"+X["results"][i]["icon"]+"'></td>";
                            txt+= "<td><div onclick=clickedThis("+ i +",'" + X['results'][i]['place_id'] + "')>" + X['results'][i]['name'] + "</div></td>";
                            //txt+= "<td><div onclick=clickedThis('" + X['results'][i]['place_id'] + "','" + X['results'][i]['name'] + "')>" + X['results'][i]['name'] + "</div></td>";
                            txt+= "<td  id = 'div" + i +"' ><div onclick=clickedMap('div"+ i +"','" + X['results'][i]['geometry']['location']['lat'] + "','" + X['results'][i]['geometry']['location']['lng'] + "')>" + X["results"][i]["vicinity"] + "</div></td></tr>";
                        }
                        txt+= "</table>";
                        document.getElementById("printHere").innerHTML=txt;
                        document.getElementById("table").value = txt;
                    }
                //console.log("got here");
                }
                
        
              var script = document.createElement("script");
              script.type = "text/javascript";
              script.src = "http://maps.googleapis.com/maps/api/js?key=AIzaSyB0mO46_Gm8eZN2-HKbl2JAp27bLKKFV3o&callback=initialize";
              document.body.appendChild(script);
                
            }
            
            function initialize(){
                mapScript = 1;
            }
            function clickedThis(i,val){
                //document.getElementById('clickedLink').value = val;
                //var m = val.split(",")[0];
                //var n = val.split(",")[1];
                var http = new XMLHttpRequest();
                http.onreadystatechange = function() {
                    if(http.readyState == 4 && http.status == 200) {
                        var respText = http.responseText;
                        var jsonText = (respText.substring(respText.indexOf("console.log(999999);")+20,respText.indexOf('"status" : "OK"')+17));
                        var photLength = parseInt(respText[respText.indexOf('"status" : "OK"')+18]);
                        //console.log(photLength);
                        
                        jsonObj = JSON.parse(jsonText);
                        var count1 = 0;
                        var count2 = 0;
                       // console.log(jsonObj['result']['reviews'].length);
                        try{
                            var len1 = Math.min(jsonObj['result']['reviews'].length,5);
                            console.log(len1);
                            document.getElementById("printHere").innerHTML= "";
                        document.getElementById("heading").innerHTML = X['results'][i]['name'];
                        document.getElementById("rev_head").innerHTML="click to show reviews";
                        document.getElementById("arr1").innerHTML="<img class='smallimg' src = \'arrow_down.png\'>";
                        
                        var reviews = "<table class='small tabcent' border=1>";
                            if(len1 == 0){
                                reviews+="<tr><td><b>No Reviews Found</b></td></tr>";
                            }else{
                                for(var k = 0; k < len1; k++){
                                    reviews+="<tr class='cent' ><td><img src='" + jsonObj['result']['reviews'][k]["profile_photo_url"] + "'><b>" + jsonObj['result']['reviews'][k]["author_name"] + "</b></td></tr>";
                                    reviews+="<tr><td>" + jsonObj['result']['reviews'][k]["text"] + "</td></tr>";
                                }
                            }
                            reviews+= "</table>";
                            var rem = document.getElementById("printHere");
                        
                        console.log(1234567);
                        document.getElementById("arr1").addEventListener("click",function(){
                            count1+=1;
                            if(count1%2==1){
                                if(count2%2==1){
                                    count2+=1;
                                    document.getElementById("photo_head").innerHTML="click to show photos";
                                    document.getElementById("arr2").innerHTML="<img class='smallimg' src = \'arrow_down.png\'>";
                                    document.getElementById("phot").innerHTML = ""; 
                                }
                                document.getElementById("rev_head").innerHTML="click to hide reviews";
                                document.getElementById("arr1").innerHTML="<img class='smallimg' src = \'arrow_up.png\'>";
                                document.getElementById("printHERE").innerHTML = reviews;    
                            }else{
                                document.getElementById("rev_head").innerHTML="click to show reviews";
                                document.getElementById("arr1").innerHTML="<img class='smallimg' src = \'arrow_down.png\'>";
                                document.getElementById("printHERE").innerHTML = "";    
                            }
                            
                            
                            
                        });
                        }
                        catch(err){
                            document.getElementById("printHere").innerHTML= "";
                        document.getElementById("heading").innerHTML = X['results'][i]['name'];
                        document.getElementById("rev_head").innerHTML="click to show reviews";
                        document.getElementById("arr1").innerHTML="<img class='smallimg' src = \'arrow_down.png\'>";
                        
                        var reviews = "<table class='small tabcent' border=1>";
                                reviews+="<tr><td><b>No Reviews Found</b></td></tr>";
                            reviews+= "</table>";
                            var rem = document.getElementById("printHere");
                        
                        document.getElementById("arr1").addEventListener("click",function(){
                            count1+=1;
                            if(count1%2==1){
                                if(count2%2==1){
                                    count2+=1;
                                    document.getElementById("photo_head").innerHTML="click to show photos";
                                    document.getElementById("arr2").innerHTML="<img class='smallimg' src = \'arrow_down.png\'>";
                                    document.getElementById("phot").innerHTML = ""; 
                                }
                                document.getElementById("rev_head").innerHTML="click to hide reviews";
                                document.getElementById("arr1").innerHTML="<img class='smallimg' src = \'arrow_up.png\'>";
                                document.getElementById("printHERE").innerHTML = reviews;    
                            }else{
                                document.getElementById("rev_head").innerHTML="click to show reviews";
                                document.getElementById("arr1").innerHTML="<img class='smallimg' src = \'arrow_down.png\'>";
                                document.getElementById("printHERE").innerHTML = "";    
                            }
                            
                            
                            
                        });
                        }
                        //console.log(jsonObj['result']['reviews'][0]["author_name"]);
                        //var_dump(debug_backtrace());
                        
                        
                        //console.log("00000");
                        
                        document.getElementById("photo_head").innerHTML="click to show photos";
                        document.getElementById("arr2").innerHTML="<img class='smallimg' src= \'arrow_down.png\'>";
                        
                        
                        var photos = "<table class='tabcent1' border=1>";
                        console.log(photLength);
                        if(photLength==0 || photLength===NaN){
                            console.log(65467);
                            photos+="<tr><td><b>No Photos Found</b></td></tr>";
                        }else{
                            for(var k = 0; k < photLength; k++){
                                var imgName = "image" + k + ".jpg";
                                photos+="<tr class='cent' ><td><a target='_blank' href='" + imgName + "'> <img width='100%' src='"+ imgName +"'></a></td></tr>";
                            }
                        }
                            photos+= "</table>";
                            var phot = document.getElementById("phot");
                            //console.log(photos);
                        
                        document.getElementById("arr2").addEventListener("click",function(){
                            count2+=1;
                            if(count2%2==1){
                                if(count1%2==1){
                                    count1+=1;
                                    document.getElementById("rev_head").innerHTML="click to show reviews";
                                    document.getElementById("arr1").innerHTML="<img class='smallimg' src = \'arrow_down.png\'>";
                                    document.getElementById("printHERE").innerHTML = ""; 
                                }
                                document.getElementById("photo_head").innerHTML="click to hide photos";
                                document.getElementById("arr2").innerHTML="<img class='smallimg' src = \'arrow_up.png\'>";
                                document.getElementById("phot").innerHTML = photos;    
                            }else{
                                document.getElementById("photo_head").innerHTML="click to show photos";
                                document.getElementById("arr2").innerHTML="<img class='smallimg' src = \'arrow_down.png\'>";
                                document.getElementById("phot").innerHTML = "";    
                            }
                        });
                            
                    //    document.getElementById("arr2").addEventListener("click",function(){
                            
                        //    var reviews = "<table>";
                        //    for(var k = 0; k < 5; k++){
                        //        reviews+="<tr><td><img src='" + jsonObj['result']['reviews'][k]["profile_photo_url"] + "'>" + jsonObj['result']['reviews'][k]["author_name"] + "</td></tr>";
                        //        reviews+="<tr><td>" + jsonObj['result']['reviews'][k]["text"] + "</td></tr>";
                        //    }
                        //    reviews+= "</table>";
                        //    document.getElementById("printHere").innerHTML = reviews;
                        //});
                        
                    }
                }
                http.open("POST", "", true);
                http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                http.send("link=https://maps.googleapis.com/maps/api/place/details/json?placeid="+ val +"&key=AIzaSyB0mO46_Gm8eZN2-HKbl2JAp27bLKKFV3o");
                
                
                
                //console.log(document.getElementById('clickedLink').value);
                //document.getElementById('form1').submit();
            }
            
            
            function clickedMap(elmt,lt,lg){
                //<div id='"+ "div" + i +"' style='position:absolute;width:250px;height:250px;' disabled></div>
                
                
                
                //var http = new XMLHttpRequest();
                //var url = "";
                //var params = "type=map&val="+elmt;
                //http.open("POST", url, true);

                //Send the proper header information along with the request
                //http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        //        http.onreadystatechange = function() {//Call a function when the state changes.
        //            if(http.readyState == 4 && http.status == 200) {
            //            alert(<?php echo $mapFlag ?>);
            //        }
                //}
                //http.send(params);


                
                if(mapScript==1){
                    var directionsDisplay = new google.maps.DirectionsRenderer;
                    var directionsService = new google.maps.DirectionsService;

                    if(mapFlag == elmt){
                        console.log(123);
                        var child = document.getElementById("x");
                        child.parentNode.removeChild(child);
                        mapFlag = "div";
                            
                    }
                    else{
                        if(mapFlag=="div"){
                            //document.getElementById(elmt).disabled=false;
                            console.log(321);
                            var printMap = document.createElement("div");
                            printMap.setAttribute("id","x");
                            printMap.style.width = "250px";
                            printMap.style.height = "250px";
                            printMap.style.position = "absolute";   
                            var hell = document.getElementById(elmt);
                            hell.appendChild(printMap);
                            var uluru = {lat: parseFloat(lt), lng: parseFloat(lg)};
                            var map = new google.maps.Map(document.getElementById("x"), {
                              zoom: 15,
                              center: uluru
                            });
                            directionsDisplay.setMap(map);

                            var marker = new google.maps.Marker({
                              position: uluru,
                              map: map
                            });
                            gmarkers.push(marker);
                            mapFlag = elmt;
                        }
                        else{
                            var child = document.getElementById("x");
                            child.parentNode.removeChild(child);
                            var printMap = document.createElement("div");
                            printMap.setAttribute("id","x");
                            printMap.style.width = "250px";
                            printMap.style.height = "250px";
                            printMap.style.position = "absolute";   
                            var hell = document.getElementById(elmt);
                            hell.appendChild(printMap);
                            var uluru = {lat: parseFloat(lt), lng: parseFloat(lg)};
                            var map = new google.maps.Map(document.getElementById("x"), {
                              zoom: 15,
                              center: uluru
                            });
                            directionsDisplay.setMap(map);
                            var marker = new google.maps.Marker({
                              position: uluru,
                              map: map
                            });
                            gmarkers.push(marker);
                            mapFlag = elmt;
                        }
                            var one = document.createElement("button");
                            one.setAttribute("id","one");
                            one.style.width = "100px";
                            one.style.height = "20px";
                            //one.style.zIndex = "5";
                            one.style.position = "absolute";
                            one.innerHTML="Walk there";
                            document.getElementById("x").appendChild(one);
                            document.getElementById('one').addEventListener('click', function() {
                                calculateAndDisplayRoute(directionsService, directionsDisplay,"WALKING",lt,lg);
                            });

                            
                            var two = document.createElement("button");
                            two.setAttribute("id","two");
                            two.style.width = "100px";
                            two.style.height = "20px";
                            two.style.marginTop = "17px"
                            //one.style.zIndex = "5";
                            two.style.position = "absolute";
                            two.innerHTML="Bike there";
                            document.getElementById("x").appendChild(two);
                            document.getElementById('two').addEventListener('click', function() {
                                calculateAndDisplayRoute(directionsService, directionsDisplay,"BICYCLING",lt,lg);
                            });
                            
                            var three = document.createElement("button");
                            three.setAttribute("id","three");
                            three.style.width = "100px";
                            three.style.height = "20px";
                            three.style.marginTop = "34px"
                            three.style.position = "absolute";
                            three.innerHTML="Drive there";
                            document.getElementById("x").appendChild(three);
                            document.getElementById('three').addEventListener('click', function() {
                                calculateAndDisplayRoute(directionsService, directionsDisplay,"DRIVING",lt,lg);
                            });
                    }
                }
            }
            
            function calculateAndDisplayRoute(directionsService, directionsDisplay, type,lt,lg) {
                var selectedMode = type;
                for(i=0; i<gmarkers.length; i++){
                    gmarkers[i].setMap(null);
                }   
                directionsService.route({
                  origin: {lat: parseFloat(<?php echo $lat?>), lng: parseFloat(<?php echo $long?>)},  // Haight.
                  destination: {lat: parseFloat(lt), lng: parseFloat(lg)},  // Ocean Beach.
                  travelMode: google.maps.TravelMode[selectedMode]
                }, function(response, status) {
                  if (status == 'OK') {
                    directionsDisplay.setDirections(response);
                  } else {
                    window.alert('Directions request failed due to ' + status);
                  }
                });
              }

         
        
            function first(){
                document.getElementById("location").disabled=true;
                document.getElementById("location").value="";
                document.getElementById("location").required=false;
            }
            function second(){
                document.getElementById("location").disabled=false;
                document.getElementById("location").required=true;
            }
            
            function resetForm(){
                document.getElementById("printHere").innerHTML="";
                document.getElementById("form1").reset();
                document.getElementById("printHere").innerHTML="";
                document.getElementById("rev_head").innerHTML="";
                document.getElementById("arr1").innerHTML="";
                document.getElementById("heading").innerHTML="";
                document.getElementById("printHERE").innerHTML="";
                document.getElementById("photo_head").innerHTML="";
                document.getElementById("arr2").innerHTML="";
                document.getElementById("phot").innerHTML="";
            }
            
            
            function send(){
                var from = document.getElementsByName("from");
                
                if(from[0].checked){
                    console.log("here clicked");
                }
                else{
                    console.log("location clicked");
                }
            }
            
            function sendphp(){
                document.getElementById("loc").value = document.getElementById("location").value;
                document.getElementById("form1").submit;
                
            }
            </script>
            
            
        
        
    </head>
    <body onload="getLoc()">
        <form id="form1" action="" method="post" onsubmit="sendphp()">
            <h1><i>Travel and Entertainment Search</i></h1>
            <hr>
            <b>Keyword</b>
            <input type="text" id="key" name="keyword" required>
            <br>
            <b>Category</b>
            <select name="category" id="cat">
                <option value="default" default>default</option>
                <option value="cafe">cafe</option>
                <option value="bakery">bakery</option>
                <option value="restaurant">restaurant</option>
                <option value="beauty_salon">beauty salon</option>
                <option value="casino">casino</option>
                <option value="movie_theater">movie theater</option>
                <option value="lodging">lodging</option>
                <option value="airport">airport</option>
                <option value="train_station">train station</option>
                <option value="subway_station">subway station</option>
                <option value="bus_station">bus station</option>
            </select>
            <br>
            <b>Distance (miles)</b>
            <input type="number" id="dist" name="dist" placeholder="10">
            <b>from</b>
            <input type="radio" id="here" name="from" value="" checked onclick="first()">Here<br>
            <input type="radio" id="loc" name="from" value="" onclick="second()"><input type="text" placeholder="location" id="location" disabled/>
            <br>
            &emsp;&emsp;&emsp;&emsp;
            <input type="submit" name="submit" id="search" value="Search" disabled>
            <input type="button" name="clear" value="Clear" onclick="resetForm()">
            <input type="hidden" name="table" id="table">
            <input type="hidden" name="clickedLink" id="clickedLink">
            <input type="hidden" id="mapFlag" name="mapFlag">
            
        </form>
        <div id="heading"></div>
        <div class="cent" id="rev_head"></div>
        <div class="cent" id="arr1"></div> 
        <div id="printHere"></div>
        <div id="printHERE"></div>
        <div class="cent" id="photo_head"></div>
        <div class="cent" id="arr2"></div> 
        <div class="cent" id="phot"></div>
    </body>
</html>