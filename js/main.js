import { MY_API_KEY } from './config.js';
let covid19data;

(function onLoad()
{
    // set a function for each button
    setButtonFunctions();

    NBADATA();
})();

function setButtonFunctions()
{

}

async function NBADATA()
{
    var $people = $("#people");
    const settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://free-nba.p.rapidapi.com/players?page=0",
        "method": "GET",
        "headers": {
            "X-RapidAPI-Key": "9fc43591cemsh6ed4d4ee703b818p14824ajsn381a6a9e8bd4",
            "X-RapidAPI-Host": "free-nba.p.rapidapi.com"
        }
    };
    
    $.ajax(settings).done(function(response) {
        console.log(response);
        $.each(response.data, function(i, people) {
            //$people.append('<li>name: ' + people.first_name + "</li>");
            $people.append(MakeCharacter(people));            
        });
    });
    $.ajax(settings).fail(function(){
        alert("Slow Down");
    });
}
function MakeCharacter(Person) {
    var Character = CharacterCreate(Person);
    //Character.appendChild(MakeFavoriteButton(Person));
    //Character.appendChild(MakeRemoveButton(Person));
    return Character;
}


//Creates a div and adds the character image and text to it
function CharacterCreate(Person) {
    var image = createImage("200px", "300px");
    var Character = document.createElement("div");
    var CharacterText = MakeDescription(Person);
    Character.setAttribute("class", "Person");
    Character.setAttribute("id", Person + "id");
    Character.appendChild(image);
    Character.appendChild(CharacterText);
    return Character;
}

//creates an image for the Character
function createImage(width, height) {
    //var imageFile = "Image/" + name + ".png";
    var image = document.createElement("img");
    if (image != null && image != undefined) {
        //image.src = imageFile;
        image.style.width = width;
        image.style.height = height;
    }
    return image;
}

//makes the text in an unordered list
//it goes through every variable in the person and creates a list using them
function MakeDescription(Person) {
    var List = document.createElement("ul");
    List.setAttribute("class", "UnorderedList")
    var count = 0;
    for (var Key in Person) {
        //if (count == 0) { }
        //else {
            var Li = document.createElement("li");
            var Text = document.createTextNode(Key + ": " + Person[Key]);
            Li.appendChild(Text);
            List.appendChild(Li);
        //}
        count++;
    }
    return List;
}
