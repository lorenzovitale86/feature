# EX001

## Work Done

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


### How To Test

To test the plugin:

1)    Install the plugin;

2)    After the installation, open a listing page and a article detail page;

3)    Create a new Customer;

4)    Do login with this new Customer, and visit again the page in 2);

5)    In the backend from the detail page of the customer change his customer group to Data Entry (DE),
      if this group is not present something has gone wrong;
     
6)    After updated the customer group, with the same user open an account page, and now should be visible the menu voice Data Entry;

7)    Open Data Entry and try to insert a player or a team without fill all fields.
 
8)    Open Data Entry and insert a new team to see if the team will be added
      
9)    After added a team, try to insert a player.

10)   With some team and some players added, open the profile page and updates the preferences of the team and player 

11)   After updating the preferences open a listing page and a article detail page, to see if the discount label appear (it should not). 

12)   Create a new article associating a team to it.

13)   Open the listing page and the detail page of this article, to see if the discount label appear. 
      Try with the article team like the customer's favorite team (it should appear the label), and try with different team (should not appear) 