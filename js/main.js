import { MY_API_KEY } from './config.js';
let covid19data;

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
    var $people = $("#people");
    
    const settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://us-real-estate.p.rapidapi.com/v2/for-rent-by-zipcode?zipcode="+Zipcode+"&limit=10&sort=lowest_price",
        "method": "GET",
        "headers": {
            "X-RapidAPI-Key": "9fc43591cemsh6ed4d4ee703b818p14824ajsn381a6a9e8bd4",
            "X-RapidAPI-Host": "us-real-estate.p.rapidapi.com"
        }
    };
    
    $.ajax(settings).done(function(response) {
        console.log(response);
        $.each(response.data.home_search.results, function(i, people) {
            //$people.append('<li>name: ' + people.first_name + "</li>");
            $people.append(MakeCharacter(people));           
        });
    });
    $.ajax(settings).fail(function(){
        alert("Slow Down");
    });
    
    
})

function MakeCharacter(Person) {
    var Character = CharacterCreate(Person);
    //Character.appendChild(MakeFavoriteButton(Person));
    //Character.appendChild(MakeRemoveButton(Person));
    return Character;
}


//Creates a div and adds the character image and text to it
function CharacterCreate(Person) {
    if(Person.primary_photo != null){
        var image = createImage(Person.primary_photo.href, "200px", "300px");
    }
    else{
        var image = createImage(Person.primary_photo, "200px", "300px");
    }
    var Character = document.createElement("div");
    var CharacterText = MakeDescription(Person);
    Character.setAttribute("class", "house");
    Character.setAttribute("id", Person + "id");
    Character.appendChild(image);
    Character.appendChild(CharacterText);
    return Character;
}

//creates an image for the Character
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
//it goes through every variable in the person and creates a list using them
function MakeDescription(Person) {
    var List = document.createElement("ul");
    List.setAttribute("class", "UnorderedList")
    var price = "null";
    if(Person.list_price != null){
        var price = "$"+Person.list_price;
    }
    else if(Person.list_price_max != null && Person.list_price_min != null) {
        var price = "$"+Person.list_price_min +" - $"+ Person.list_price_max;
    }
    else{
        var price = "To Be Determined";
    }
    var NewTest = new Test(Person.listing_id, Person.description.type, Person.location.address.line, Person.location.address.city, Person.location.address.state, Person.location.address.postal_code, 
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