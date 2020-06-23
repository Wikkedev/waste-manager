# waste-manager
collect and treat waste

Diagram UML 

The abstract class Waste is to set weight, co2 and district attribut for all subClasses.
Weight, co2 arer int and district is an array. Whith district attribute, i can know where Waste comes from.

I made a class for all the categories of waste and a subclass for the way in which it is treated. (plastic is an abstract class)
Like that, if there is a another way to treat waste, i can add a new class for it without editing any class.
I think (and i hope) it is S.O.L.I.D.
you can see that all classes have an incineration interface. 
I dont put interface incineration on Waste class because if we decide to not incinerate a new type of plastic, i can create a new class for this plastic without incineration.
I imagine this exemple on the left of the UML in grey.
To see if my way of doing is S.O.L.I.D., i add a new class (in grey) for Pvc call PvcTraitement implementing NewInterfaceTraitement.
It works.
If it is necessary, i can add new subclasses for paper like white or colored without editing paper class. 

For Services with an attribut Nom(string), I made SubClasses for all services with their respective attributes. 
Capacity attribute is to compare with weight attribute of waste to find out if we can treat it (with addition of waste).
For incinerator, ligneFour is an int and capaciteLigne is int. 
For RecyclingPlastic, plastic atribute is an array. I can instanciate many recyclingPlastic with some value presented in the array. 
I have just to add value in my array when ther is a new one and choose one of them when i instanciate.
For Composter, foyer is an int.
There is no atttribut for RecyclingGlass, RecyclingMetals and SortingCenter.
I made the UML with the sortingCenter.

Spend time :
I done it in 2h30, 1h45 for the graphic and 0h45 for explain.
it took me half an hour to create the file tree.

Encountered problem :
At the start of the exercise, I wanted to simplify too much by imagining for example that composting was a kind of recycling.
So no need to make a particular class. And all the elements present in the JSON are incinerable so I implement incineration on waste.

I just forgot to ask myself a type of question: What if we add that? What if we create a new way to deal with waste? If we create a new waste?, If a new waste is not incinerable? ....
And how to assign a quantity of CO2 released according to the treatment for each waste?

In this way, I can send a waste to a new branch by assigning a new amount of released CO2 to it. I can add the weight of each waste to find out if the service can support it.

I think now I can add or remove any element of the diagram without having to modify its structure.
My UML so the resulting code is S.O.L.I.D.

At this time (Sunday 23:00), I have to write the code in the files, create the class with their attributes, their methods, their extends, their implementations, the interfaces, the getter.
But above all the index.php file to display the requested results.

------------------------------------------------
Lundi 23/06 
J'ai corrigé certaines information de l'UML au fur et à mesure des besoins et des oublies. Mais je n'est pas changé sa structure.
Je recupere les capacités de traitement de chaque  service en verifiant si ce service existe pour ne pas l'instancier pour rien.
Je recupere par quartier (pour afficher le résultat de la répartition des déchets) pour chaque dechet son nom, son tonnage, le CO2 regeté en fonction du mode de traitement. J'additionne le tout pour la quantité total du dechet. 
Je vais passer cette quantité total à la moulinette en commencant par recycler puis incinérer ce qui reste. Je recuperer la quantité de CO2 regeté en fonction de chaque traitement pour chaque dechet.
J'avance en corrigeant les erreurs que produit mon code qui s'affiche dans la console. Je cherche des solutions dans oop-project-management ou sur internet. 
C'est plus une question de technique et de manque de connaissance en POO car je comprend le principe (c'est bien).
J'en suis là. J'y ai passé 6 heures.
j'upload mes fichiers.

--------------------------------------------------
Mardi 24/06
Je travail depuis ce matin sur l'application et je commence à avoir un résultat. 
Mais je me rend compte que je n'est pa pris en compte le fait qu'il y ai 3 incinérateurs. J'ai fait comme s'il n'y en avait qu'un.
Dans ma logique, je dois vérifier la quantité de dechets à incinérer et voir si le premier incinérateur peut la traiter. Si non, il faut que j'envoie le reste au deuxieme puis au troisieme.
