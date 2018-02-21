Registrarsi a shopify partners

Fare il login e cliccare su Apps

Creare l'applicazione e inserire come app url la propria installazione /shopapp

Andare nelle informazioni dell'applicazione e inserire in whitelisted redirection url(s) le url sia https sia http della propria webapp seguida con /shopapps/response

Andare in app credentials e prendere Api key e Api secret key e inserirle in application/config/constants.php alla linea 90 e 91

Aprire il file application/config/database.php e aggiungere le proprie informazioni alla linea 17-18-19

Importare shopify.sql nel proprio database 

Recarsi alla webapp e inserire l'url del proprio shopify come descritto

Accettare l'installazione dell'app in shopify

Andare in settings nella schermata successiva e inserire l'apikey di fattura24, se mandare l'email e il tipo di template da usare


Impostare codice fiscale / partita iva al checkout
Andare in impostazioni del tema del proprio shopify, cliccare su online store, themes, selezionare edit languages, cercare la stringa Address2 e modificare come segue
