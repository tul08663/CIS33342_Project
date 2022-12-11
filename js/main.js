import { MY_API_KEY } from './config.js';


function Test(Id, Type, Street, City, State, Postal, Price) { // constructor header (signature)
    this.Id = Id;
    //this.Title = Title;
    this.Type = Type;
    this.Street = Street;
    this.City = City;
    this.State = State;
    this.Postal = Postal;
    this.Price = Price;
}

(function onLoad()
{
})();

$('#btnZip').click(function()
{
    var Zipcode = $("#Zipcode").val();
    console.log(Zipcode);
    var Houses = $("#Houses");
    Houses.empty();


    
    const settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://us-real-estate.p.rapidapi.com/v2/for-rent-by-zipcode?zipcode="+Zipcode+"&limit=20&sort=lowest_price",
        "method": "GET",
        "headers": {
            "X-RapidAPI-Key": "9fc43591cemsh6ed4d4ee703b818p14824ajsn381a6a9e8bd4",
            "X-RapidAPI-Host": "us-real-estate.p.rapidapi.com"
        }
    };
    
    $.ajax(settings).done(function(response) {
        console.log(response);
        $.each(response.data.home_search.results, function(i, house) {
            //$people.append('<li>name: ' + people.first_name + "</li>");
            Houses.append(MakeListing(house));           
        });
    });
    $.ajax(settings).fail(function(){
        alert("Retry");
    });
    
    
})

//Creates a div and adds the character image and text to it
function MakeListing(House) {
    if(House.primary_photo != null){
        var image = createImage(House.primary_photo.href, "200px", "300px");
    }
    else{
        var image = createImage(House.primary_photo, "200px", "300px");
    }
    var HouseDetails = document.createElement("div");
    var CharacterText = MakeDescription(House);
    HouseDetails.setAttribute("class", "house");
    HouseDetails.setAttribute("id", House + "id");
    HouseDetails.appendChild(image);
    HouseDetails.appendChild(CharacterText);
    return HouseDetails;
}

//creates an image for the HouseDetails
function createImage(name, width, height) {
    var imageFile = name;
    var image = document.createElement("img");
    //if (image != null && image != undefined) {
        image.src = imageFile;
        image.style.width = width;
        image.style.height = height;
    //}
    return image;
}

//makes the text in an unordered list
//it goes through every variable in the House and creates a list using them
function MakeDescription(House) {
    var List = document.createElement("ul");
    List.setAttribute("class", "UnorderedList")
    var price = "null";
    if(House.list_price != null){
        var price = "$"+House.list_price;
    }
    else if(House.list_price_max != null && House.list_price_min != null) {
        var price = "$"+House.list_price_min +" - $"+ House.list_price_max;
    }
    else{
        var price = "To Be Determined";
    }
    var NewTest = new Test(House.listing_id, House.description.type, House.location.address.line, House.location.address.city, House.location.address.state, House.location.address.postal_code, 
        price)
    console.log(NewTest);
        var count = 0;
        for (var Key in NewTest) {
            var Li = document.createElement("li");
            var Text = document.createTextNode(Key + ": " + NewTest[Key]);
            Li.appendChild(Text);
            List.appendChild(Li);
        }
        return List;
    }
