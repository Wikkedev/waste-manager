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

22h22 -> apres recherche sur internet, je viens de decider qu'un foyers était une unité de compostage ayant une certaine capacité. (capacité x foyer)
Les capacités de traitement des composteurs sont trés petites par rapport à la totalité des dechets organiques à traiter. Je decide que tous les composteurs ne forme qu'une seule capacité.

ce soir, j'arrive à produire ce resultat :

PET -- est un nouveau type de déchet. Il faut l'ajouter au programme de traitement. Contactez votre CDA préféré.
PVC -- est un nouveau type de déchet. Il faut l'ajouter au programme de traitement. Contactez votre CDA préféré.
PC -- est un nouveau type de déchet. Il faut l'ajouter au programme de traitement. Contactez votre CDA préféré.
PEHD -- est un nouveau type de déchet. Il faut l'ajouter au programme de traitement. Contactez votre CDA préféré.
_________________________________________________________________________________________________
Quantite total de papier a traiter : 66941.8 Tonnes
Le centre de tri d'une capacité de 73922 tonnes peut traiter la totalité du papier.
Mais la filiaire de traitement du papier d'une capacité de 36961 tonnes ne peut pas.
29980.8 tonnes de papier vont directement être envoyés à l'incinérateur.
Le recyclage de 36961 tonnes de papier a produit 184805 grammes de CO2.
L'incinération de 29980.8 tonnes de papier dans l'incinérateur 1 a produit 749520 grammes de CO2.
Le traitement de 66941.8 tonnes de papier a produit 934325 grammes de CO2 toutes filiaires de traitement confondues.
_________________________________________________________________________________________________
_________________________________________________________________________________________________
Quantite total de dechets organiques a traiter : 98060.31 Tonnes
Le centre de tri ne peut traiter que 73922 tonnes de dechets organiques. Le reste (24138.31 tonnes) va être incinéré.
Le compostage de 73922 tonnes de dechets organiques a produit 73922 grammes de CO2.
L'incinération de 24138.31 tonnes de dechets organiques dans l'incinérateur 1 a produit 675872.68 grammes de CO2.
Le traitement de 98060.31 tonnes de dechets organiques a produit 749794.68 grammes de CO2 toutes filiaires de traitement confondues.
_________________________________________________________________________________________________
_________________________________________________________________________________________________
Quantite total de verre a traiter : 42011.45 Tonnes
Le centre de tri d'une capacité de 73922 tonnes peut traiter la totalité du verre.
Mais la filiaire de traitement du verre d'une capacité de 20791 tonnes ne peut pas.
21220.45 tonnes de verre vont directement être envoyés à l'incinérateur.
Le recyclage de 20791 tonnes de verre a produit 124746 grammes de CO2.
L'incinération de 21220.45 tonnes de verre dans l'incinérateur 1 a produit 1061022.5 grammes de CO2.
Le traitement de 42011.45 tonnes de verre a produit 1185768.5 grammes de CO2 toutes filiaires de traitement confondues.
_________________________________________________________________________________________________
_________________________________________________________________________________________________
Quantite total de metaux a traiter : 10054.33 Tonnes
Le centre de tri d'une capacité de 73922 tonnes peut traiter la totalité des metaux.
la filiaire de recyclage des metaux peut traiter tout les metaux.
Le recyclage des metaux a produit 70380.31 grammes de CO2.
Le traitement de 10054.33 tonnes de metaux a produit 70380.31 grammes de CO2 toutes filiaires de traitement confondues.
_________________________________________________________________________________________________
_________________________________________________________________________________________________
Quantite total de dechets divers (autre) a traiter : 687825.49 Tonnes.
Le centre de tri ne traite aucun de ces dechets divers (autre). Tout (687825.49 tonnes) va être incinéré.
L'incinération de 665300 tonnes de dechets divers (autre) dans l'incinérateur 1 a produit 19959000 grammes de CO2.
L'incinération de 22525.49 tonnes de dechets divers (autre) dans l'incinérateur 2 a produit 675764.7 grammes de CO2.
Le traitement de 687825.49 tonnes de dechets divers (autre) a produit 20634764.7 grammes de CO2 toutes filiaires de traitement confondues.
_________________________________________________________________________________________________

Je dois encore travailler sur le fichiers organique.php car les resultats ne sont pas complets.
Je vais faire un tableau recapitulatif du CO2 produit par recyclage, compostage et incineration.

J'ai une question sur les methodes de treatment (incineratorTreatment, recyclingTreatment et compostingTreatment) que je suis obligé de les mmettre dans les class waste et les class services. Si je ne fais pas ça, php retourne une erreur.

--------------------------------------------------------------------------
Mercredi 24/06
problemes reglés sur organique.php
Le MVC n'est pas respecté mais je le sais.
Le tableau recapitulatif est en cours.

Je voulais faire ressortir quelle quantité de CO2 avez été rejeté par quartier mais je n'y arrive pas. J'ai traité les dechets dans leur globalité.
Je dois repenser le prorcessus de traitement pour ressortir une valeur par quartier. Du coup, j'ai pas beaucoup avancé. 
Ce que j'affiche tout de suite est juste mais pas détaillé.

