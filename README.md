# shopflix-connect 

[![GitHub version](https://badge.fury.io/gh/OnecodeGr%2Fshopflix-connector-woocommerce.svg)](https://badge.fury.io/gh/OnecodeGr%2Fshopflix-connector-woocommerce)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](./LICENSE.md)

## 1. Πως να εγκαταστήσετε Plugin ShopFlix.

Κατεβάζετε το Zip αρχείο. Στη συνέχεια πηγαίνετε Plugins -> Προσθήκη νέου και κάνετε μεταφόρτωση το Zip αρχείο.

## 2. Στο Μενού το Wordpress εμφανίζεται το Plugin ShopFlix.

Πατώντας το ShopFlix Api Settings μεταφερόμαστε στο περιβάλλον ρυθμίσεων.

Βασική προϋπόθεση για να λάβουμε παραγγελίες από το ShopFlix είναι να ενεργοποιήσουμε την γέφυρα.

![Imgur](https://i.imgur.com/fnMfE8Z.png)

Για να μεταφέρουμε τις παραγγελίες στο Woocommerce θα πρέπει να έχουμε ενεργοποιήσει το Convert to Woocommerce Orders.

Υπάρχει η δυνατότητα της αυτόματης μετατροπής της παραγγελίας σε Woocommerce Order.

Αφού ενεργοποιήσετε το ShopFlix για να λάβετε παραγγελίες θα πρέπει να έχετε συμπληρώσει τα στοιχειά Api Url, Username και Password που σας έχουν στείλει.

![Imgur](https://i.imgur.com/i52FoU8.png)

Προσοχή! Ορισμένες εταιρίες Hosting έχουν κλειστό τον Cron.

Έαν δεν ενεργοποιήσετε τον Cron του Wordpress μπορείτε να ορίσετε χειροκίνητα
από τον Server τον Cron να καλεί το URL που θα δημιουργηθεί στην εγκατάσταση του Plugin κάθε 1 ώρα.

ShopFlix Api Settings -> Wordpress Cron

![Imgur](https://i.imgur.com/i7XVJuo.png)

## 3. Δημιουργία XML

Ενεργοποιώντας την λειτουργία XML δημιουργούνται τα 2 XML που είναι απαραίτητα για την εμφάνιση των προϊόντων στο ShopFlix. Τα XML ανανεώνονται κάθε 15 λεπτά.

Πεδία MPN, EAN, Manufacturer

Εάν έχετε δημιουργήσει τα πεδία MPN, EAN και Manufacturer στην σελίδα των προϊόντων σας τότε μπορείτε να ορίσετε το αντίστοιχο πεδίο. Εάν δεν έχετε δημιουργήσει τα πεδία τότε το Plugin μέσα σε κάθε Simple Product ή Variation product δημιουργεί τα παρακάτω στοιχεία.

![Imgur](https://i.imgur.com/qkC1a7S.png)

## 4. Λήψη παραγγελιών και διαδικασία

Η παραγγελίες με Status pending Acceptance είναι οι νέες παραγγελίες που το σύστημα αναμένει την διαχείρισή της.
Πατώντας Accept αποδέχεστε την παραγγελία και αν έχετε ενεργοποιήσει την λειτουργία μεταφοράς σε woocommerce θα δημιουργηθεί νέα παραγγελία σε κατάσταση «σε Επεξεργασία» με τα στοιχεία πελάτη από το Shopflix και τα αντίστοιχα προϊόντα.

Πατώντας Reject απορρίπτετε την παραγγελία. Προσοχή εάν μια παραγγελία έχει γίνει Accept δεν μπορεί να γίνει μετά Reject.

![Imgur](https://i.imgur.com/xOPI2ZV.png)

Αφού η παραγγελία γίνει accept είναι σε Status picking. Μόλις ολοκληρώσετε το Picking της παραγγελίας πατάτε «Έτοιμο προς Παραλαβή»
![Imgur](https://i.imgur.com/SnwOdTf.png)
Στη συνέχεια μπορείτε να εκτυπώσετε το Voucher να ετοιμάσετε το πακέτο και ο Courier θα έρθει να παραλάβει την παραγγελία.
