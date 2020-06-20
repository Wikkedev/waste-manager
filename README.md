# waste-manager
collect and treat waste

Diagram UML 

The Waste class is to set weight, co2 and district attribut for all subClasses.
Weight, co2 arer int and district is an array. Whith district attribute, i can know where Waste comes from.

I made a class for all the categories of waste and a subclass for the way in which it is treated.
Like that, if there is a another way to treat waste, i can add a new class for it without editing any class.
I think (and i hope) it is S.O.L.I.D.
you can see that all classes have an incineration interface. 
I dont put interface incineration on Waste class because if we decide to not incinerate a new type of plastic, i can create a new class for this plastic without incineration.
I imagine this exemple on the left of the UML in grey.
To see if my way of doing is S.O.L.I.D., i add a new class (in grey) for Pvc call PvcTraitement implementing NewInterfaceTraitement.
It works.
If it is necessary, i can add new subclasses for paper like white or colored without editing paper class. 

For Services with an attribut Capacity(int), I made SubClasses for all services with their respective attributes. 
Capacity attribute is to compare with weight attribute of waste to find out if we can treat it (with addition of waste).
For incinerator, ligneFour is an int. 
For RecyclingPlastic, plastic atribute is an array. I can instanciate many recyclingPlastic with some value presented in the array. 
I have just to add value in my array when ther is a new one and choose one of them when i instanciate.
For Composter, foyer is an int.
There is no atttribut for RecyclingGlass, RecyclingMetals and SortingCenter.
I made the UML with the sortingCenter.

Temps passé :
I done it in 2h30, 1h45 for the graphic and 0h45 for explain.

Problemes rencontrés :
Au depart de l'exercice, j'ai voulu trop simplifier en imaginant par exemple que le compostage était une sorte de recyclage. 
Donc pas besoin de faire une class particuliere. Et tous les élément présent dans le JSON sont incinérables donc j'implément incineration sur waste. 

J'ai juste oublié de me poser un type de question : Et si on ajoute ça ? Si on crée un nouveau moyen de traiter les dechets ? Si on crée un nouveau dechet ?, Si un nouveau déchets n'est pas incinérable ? ....
Et comment assigner une quantité de CO2 dégagé en fonction du traitement à chaque dechets ? 

De cette manière, je peux envoyer un dechet dans une nouvelle filiare en lui assignant une nouvelle quantité de CO2 degagé. Je peux additionner le poids de chaque déchets pour savoir si le service peut le supporter.

Je pense que maintenant, je peux ajouter ou enlever n'importe quel éléments du diagramme sans devoir modifier sa structure. 
Mon UML donc le code qui en decoulera est S.O.L.I.D.



