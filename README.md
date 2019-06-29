deliveryCharges - discounts
Normally these would be read from a db.
I didn't declare argument types on these methods as we could pass multiple types depending on future coding requirements.
We will cycle through the deliverCharges array to find the charge when the amount is <90.
Delivery charges will be ascending on total based on code written.

Buy one get one half price will match the 2 items in the basket and apply a 50% discount rounded up to nearest penny on 1 item.
We have to round up otherwise technically we are charging more than half price.
On a 3 item purchase we only discount 1 item.

I have returned the amounts unformatted as we could be dealing with non decimal currency (Japanese for example) although I am aware the test script uses dollars.

I have used a combination of arrays, methods, functions and classes.

I used php 7.1 for this code.

To test the code change the value of x on line 125 to match the pre supplied configurations, or enter your own code based on one of the case examples.
I tested this by running a basic php server on a macbook and calling index.php from the browser.

I refrained from using a function called 'roadRunner' even though it was Acme Widgets, and I believe I should be awarded extra points for this.


