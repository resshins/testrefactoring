# Business rules


`BR-1`: At the end of each day our system lowers both values for every item
`BR-2`: Once the sell by date has passed, Quality degrades twice as fast
`BR-3`: The Quality of an item is never negative
`BR-4`: "Aged Brie" actually increases in Quality the older it gets
`BR-5`: The Quality of an item is never more than 50
`BR-6`: "Sulfuras", being a legendary item, never has to be sold or decreases in Quality
`BR-7`: "Backstage passes", like aged brie, increases in Quality as its SellIn value approaches; Quality increases by 2 when there are 10 days or less and by 3 when there are 5 days or less but Quality drops to 0 after the concert
`BR-8`: We have recently signed a supplier of conjured items. This requires an update to our system:
`BR-9`: "Conjured" items degrade in Quality twice as fast as normal items