## Web app per applicazione privata shopify-fattura24

Registrarsi a shopify partners

Fare il login e cliccare su Apps
![login](https://i.imgur.com/lq9h8PL.png)

Creare l'applicazione e inserire come app url la propria installazione /shopapp
![creazione](https://i.imgur.com/JXejsss.png)

Andare nelle informazioni dell'applicazione e inserire in whitelisted redirection url(s) le url sia https sia http della propria webapp seguida con /shopapps/response
![webhook](https://i.imgur.com/nJpxWXt.png)

Andare in app credentials e prendere Api key e Api secret key e inserirle in application/config/constants.php alla linea 90 e 91
![credenziali](https://i.imgur.com/p4yxlg8.png)

Aprire il file application/config/database.php e aggiungere le proprie informazioni alla linea 17-18-19
![database](https://i.imgur.com/mbry9eT.png)

Importare shopify.sql nel proprio database 

Recarsi alla webapp e inserire l'url del proprio shopify come descritto
![webapp](https://i.imgur.com/YUioJXn.png)

Accettare l'installazione dell'app in shopify
![installazione](https://i.imgur.com/qGpYjhT.png)

Andare in settings nella schermata successiva e inserire l'apikey di fattura24, se mandare l'email e il tipo di template da usare
![api fattyra24](https://i.imgur.com/aXp0ktr.png)

## Impostare codice fiscale / partita iva al checkout

Andare in impostazioni del tema del proprio shopify, cliccare su online store, themes, selezionare edit languages, cercare la stringa 
![language](https://i.imgur.com/7tHCOuv.png)

Address2 e modificare come segue
![address2](https://i.imgur.com/hV9raVf.png)
