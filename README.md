# EX002

## Work Done

When creating a new article, it's possible to set that's a gadget.

When creating a new article, it's possible to associate a gadget to it.

When a customer add to cart an article that has a team associated like the one he selected as his favorite, if the article has a gadget associated, it will be added once to the cart without price.

When a gadget is in the cart, if the customer remove it, he will be asked to confirm if he want to remove it.

When an article is removed from the cart, if in the cart is present a gadget associated to the article it will be removed.

Removed the gadget articles from the listing, the search results and the details (if is not in the cart)



### Work Done In EX001

Created two new models:

    -    Team (Name, Logo, Collection of Players)
    -    Player (Name, Surname, Age, Team)
    
Added a new customer group called Data Entry (DE), with the privileges to create new Team and new Player.

From the backend is possible to assign this group to any customer.

Every Player must have one and only one Team associated.

A Team may not have a player associated, but may have many players.

Added two new attributes for the customer for the favorites team and player.

The customer may update his favorites team and player from his profile page.

From the backend is possibile to modify this attribute for every customer.

Added a new attribute for the article to associate a team it.

Added functionality that if an article has a team associated and the customer logged in has the same team as his favorite
shows in the carousel and in the list of articles a label with a star icon and discount text; the same in the detail page 
of the article.


#### How To Test

Create an article and check gadget field, then try to search it or go to listing page and see if is listed (it should not).

Create an article and assign a gadget to it. Add to cart the article without any matching between article team and favorite customer's team.
Nothing should happens except the default action of add the article to car.

Add to cart the article with a gadget assigned and a team that match the favorite customer's team. 
Now in addition to the article, it will added to the cart 1 and only 1 gadget assigned to the article.

With the article and his gadget in the cart, add another article of the same kind, and see if another gadget is added (it should not).

Check if the remove button for the gadgets appear only in the checkout confirm page.
Remove the gadget and you will be asked to confirm the removing of it (only for the gadget)

Remove the article and the associated gadget should be removed as well. 