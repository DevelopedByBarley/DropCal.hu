2023.04.04

Bejelentkezés nélkül ---------------------------------------------------------------------------------------

user fellép az oldalra => minden 0-ázva van és 1 napig menti amit beír, lapozni nem tud a napok között,
receptek és minden random ami megjelenik a weboldalon

megoldás => cookie , 24óránként töröljük az adatbázist





Bejelentkezéssel -------------------------------------------------------------------------------------------

user fellép az oldalra => első napot megjelenítjük 0-zva, dátumot választani tud, és minden nap mentődik 




// SQL automatikusan törlő parancsa a public_diary-ra
CREATE EVENT daily_delete ON SCHEDULE EVERY 1 DAY STARTS '2023-04-05 00:00:00' DO DELETE FROM my_table;


DROP EVENT daily_delete;


I-----> Belépő, public page kirenderelése