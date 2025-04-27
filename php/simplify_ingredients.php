<?php
/**
 * Author: Stephanie
 * Student Number: 400562559
 * Date Created: 2025/04/24
 * Description: Matches raw ingredients list with common ingredients
 */

/**
 * Takes the scraped ingredients list from a recipe and returns and array of all the common ingredients found
 * @param {String array} rawIngredients
 * @return an array of all the common ingredients found in the array
 */
function simplify_ingredients(array $rawIngredients): array {
    $commonIngredients = [
        "almond flour", "flour", "sugar", "baking powder", "salt", "milk", "bacon", "egg", "butter", "vanilla extract", "celery", "chicken", "sweet potatoes",
        "oil", "pepper", "onion", "garlic", "cheese", "tomato", "bread", "rice", "olive oil", "garlic powder", "cherry tomatoes", "white wine",
        "white rice", "cinnamon", "ketchup", "soy sauce", "mayonnaise", "vegetable oil", "brown sugar", "oregano", "coconut milk", "fish filets",
        "potato", "honey", "paprika", "baking soda", "spaghetti", "peanut butter", "chili powder", "cumin", "mustard", "bell peppers", "fish",
        "chicken breast", "cheddar", "onion powder", "carrot", "basil", "parsley", "parmesan", "italian seasoning", "marinara sauce", "pasta", "marshmallows",
        "thyme", "bell pepper", "scallion", "red onion", "avocado", "zucchini", "shallot", "cherry tomato", "cucumber", "mozerella cheese",
        "corn", "spinach", "sweet potato", "broccoli", "baby greens", "pumpkin", "cauliflower", "asparagus", "cabbage", "baked beans", "vinegar",
        "arugula", "kale", "leek", "lettuce", "eggplant", "butternut squash", "romaine", "beetroot", "brussels sprout", "pepperoni", "sesame oil",
        "fennel", "radish", "sun dried tomato", "red cabbage", "artichoke", "summer squash", "new potato", "mixed greens", "eggs", "steaks",
        "parsnip", "baby carrot", "sweet pepper", "green tomato", "iceberg", "watercress", "mixed vegetable", "horseradish", "salmon", "beef broth",
        "mashed potatoes", "chard", "hash browns", "pimiento", "butter lettuce", "napa cabbage", "spaghetti squash", "feta cheeese", "broth",
        "coleslaw", "celeriac", "turnip", "bok choy", "okra", "acorn squash", "corn cob", "radicchio", "water chestnut", "grape tomatoes", "tofu",
        "pearl onion", "cavolo nero", "leaf lettuce", "tenderstem broccoli", "baby bok choy", "jicama", "collard greens", "rib eye steak", "couscous",
        "plantain", "endive", "corn husk", "french-fried onions", "daikon", "baby corn", "peas and carrots", "potato flakes", "steak", "pine nuts",
        "belgian endive", "rutabaga", "broccoli rabe", "kohlrabi", "microgreens", "yam", "boston lettuce", "delicata squash", "maple syrup", "turkey",
        "asian eggplant", "cress", "broccoli slaw", "frisee", "golden beet", "french fries", "banana pepper", "gem lettuce", "cocoa powder",
        "alfalfa", "jerusalem artichoke", "lamb's lettuce", "kabocha squash", "red leaf lettuce", "lemon", "lime", "apple", "oats", "tomatoes",
        "banana", "orange", "raisins", "mango", "pineapple", "peach", "date", "coconut", "craisins", "pear", "pomegranate", "espresso powder",
        "grape", "watermelon", "rhubarb", "dried apricot", "kiwi", "grapefruit", "plum", "fig", "apricot", "mandarin", "peas", "onions", "carrots",
        "currant", "prunes", "cantaloupe", "heavy cream", "sour cream", "buttermilk", "yogurt", "greek yogurt", "cream", "potatoes", "jalapeno", "beans",
        "whipped cream", "ghee", "shortening", "half and half", "sweetened condensed milk", "evaporated milk", "ice cream", "tilapia",
        "margarine", "creme fraiche", "frosting", "milk powder", "curd", "thickened cream", "lemon curd", "custard", 
        "dulce de leche", "chocolate frosting", "liquid egg substitute", "kefir", "sherbet", "hung curd", "liquid egg whites", 
        "fried eggs", "chocolate milk", "whey", "quail egg", "buttermilk powder", "khoya", "frozen yogurt", "poached eggs", 
        "coffee creamer", "cheese curd", "clotted cream", "sour milk", "milk cream", "goat milk", "scrambled eggs", 
        "ice-cream sandwich", "ganache", "duck egg", "salted egg", "skyr", "pumpkin spice coffee creamer", "yogurt starter", 
        "honey greek yogurt", "raw milk", "amul cream", "lime curd", "powdered coffee creamer", "milkfat", "strawberry frosting", 
        "amul butter", "cajeta", "rainbow sherbet", "honey butter", "strawberry cream cheese", "goat yogurt", "chocolate milk mix", 
        "goat butter", "starter culture", "peppermint mocha creamer", "century egg", "passionfruit curd", "sheep-milk yogurt", 
        "orange curd", "dahi", "cinnamon sugar butter spread", "gluten-free baking flour", "white cornmeal", "gluten-free cake mix", 
        "millet flour", "einkorn flour", "self-rising cornmeal", "quick-cooking tapioca", "ready-made icing", "gluten-free self-raising flour", 
        "peanut flour", "coconut powder", "soy flour", "butter cake mix", "teff flour", "guar gum", "fruit salt", "meringue nest", 
        "sweet bean paste", "potato flour", "tapioca pearls", "amaranth flour", "pie crust", "puff pastry", "pizza crust", "biscuit dough", 
        "refrigerated crescent rolls", "phyllo", "dumpling wrapper", "graham cracker crust", "cookie dough", "rice paper", 
        "sourdough starter", "egg roll wrapper", "cinnamon roll dough", "bread dough", "butter puff pastry", "bread", "bread crumbs", 
        "panko", "flour tortillas", "corn tortillas", "crackers", "baguette", "tortilla chips", "pretzels", "pita", "seasoned bread crumbs", 
        "sourdough bread", "popcorn", "rustic italian bread", "croutons", "whole-wheat tortillas", "english muffin", "brioche", "rye bread", 
        "flatbread", "dry-roasted peanuts", "stuffing mix", "potato chips", "naan", "taco shells", "unpopped popcorn", "corn chips", 
        "cornbread", "tater tots", "bagel", "croissants", "pork rinds", "pumpernickel", "gluten free bread", "hawaiian rolls", 
        "sweet bread", "pita chips", "potato bread", "breadsticks", "focaccia", "tostada shells", "muffin", "crispy fried onions", 
        "gluten-free bread crumbs", "matzo", "yeast extract spread", "garlic bread", "cornbread stuffing mix", "roasted gram", 
        "cheese crackers", "challah", "panettone", "gluten-free tortillas", "rice cake", "crostini", "sev", "papad", "corn muffin", 
        "vegetable chips", "spinach wraps", "plantain chips", "roasted chickpeas", "pretzel bun", "sprouted bread", "boboli", "seed bread", 
        "cheetos", "crispbread", "crispy noodles", "chapati", "keto bread", "low-carb wraps", "bread bowl", "milk bread", "rusks", 
        "frozen onion rings", "pav bun", "corn nuts", "rice crackers", "banana bread", "crumpet", "fruit bread", "polarbröd", "roti bread", 
        "chocolate muffin", "prawn crackers", "popcorn shrimp", "puffed corn", "wasabi peas", "ezekiel bread", "melba toast", "bao bun", 
        "barley rusks", "breading mix", "yorkshire pudding", "arabic bread", "caramel popcorn", "seaweed snack", "sandwich crackers", 
        "zwieback", "dijon mustard", "worcestershire", "hot sauce", "ketchup", "mustard", "fish sauce", "bbq sauce", "sriracha", 
        "wholegrain mustard", "tamari", "ginger-garlic paste", "oyster sauce", "chili sauce", "brown mustard"
    ];
    
    $simplified = [];

    foreach ($rawIngredients as $line) {
        $line = strtolower($line);
        // Remove punctuation
        $line = preg_replace("/[^\w\s]/", "", $line);
        // Split into words
        $words = explode(" ", $line);

        for ($i = 0; $i < count($words); $i++) {
            $single = $words[$i];
            $double = isset($words[$i + 1]) ? $single . " " . $words[$i + 1] : null;

            if ($double && in_array($double, $commonIngredients)) {
                $simplified[] = $double;
                $i++; // skip next word since it’s already used
            } elseif (in_array($single, $commonIngredients)) {
                $simplified[] = $single;
            }
        }
    }

    // Remove duplicates
    return array_values(array_unique($simplified));
}
?>
