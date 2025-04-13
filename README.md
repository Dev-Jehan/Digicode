Executer la requête SQL suivante après avoir ajouté la BDD dans le localhost afin d'ajouter la colonne 'Digicode' dans la table mrbs_room :
ALTER TABLE mrbs_room
ADD COLUMN digicode VARCHAR(20) NOT NULL DEFAULT '123456';
