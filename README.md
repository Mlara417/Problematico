# Problematico
API that compiles development problems into an accessible format. 

### Start Server
symfony server:start 

### Check Exposed Env Variables
symfony var:export

### DB Dump
symfony run pg_dump --data-only > dump.sql 

### DB Restore 
symfony run psql < dump.sql

### **IF pg_dump is not available, run (MacOS):**
brew install libpq

Should be available in /opt/homebrew/Cellar/libpq/15.3_1/bin/pg_dump
