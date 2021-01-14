Om te communiceren tussen microservices zijn er verschillende methodes, de 2 meest gebruikte zijn HTTP Rest en (g)RPC.

- HTTP Rest:
  
  Gewoon standaard HTTP requests.


- (g)RPC:
 
  (g)RPC (Google) Remote Procedure Calls is een variant van RPC gemaakt door Google. Dit is de meest gebruikte variant omdat er integraties zijn voor veel talen en er HTTP/2 wordt gebruikt voor transportatie. 
  Als IDL (Interface Descriptive Language) gebruikt gRPC [PotoBuf (Protocol Buffers)](https://developers.google.com/protocol-buffers). Je dient al je datamodellen en requests uit te schrijven in ProtoBuf. Op basis van deze ProtoBuf bestanden worden dan via een package classes gegenereerd in jouw gekozen programmeertaal. Die classes kun je vervolgens gebruiken om requests te maken. [voorbeeld](https://gist.github.com/tsh-code/2bfb2b5742c456e3e7615298f0d844f0#file-bookstore-proto)

gRpc werkt erg goed. Echter, vind ik zelf dat het schrijven van al die protobuf bestanden een pain-in-the-ass is. Vooral omdat je vaak al een bestaande applicatie hebt die afhankelijk is van HTTP Rest calls. Om deze reden heb ik YoungOnes/Lightspeed gemaakt.

Wat Lightspeed doet is het draaien van een TCP (network protocol) server. Daarop worden requests afgevangen. Deze requests worden vertaald naar reeds bestaande routes in de applicatie. Deze routes worden intern uitgevoerd, en de data die uit deze routes komt wordt vervolgens over het zelfde TCP socket teruggestuurd.

Voor de gein (en de snelheid die het meebrengt) wordt de data die over de TCP socket verstuurd wordt geencode als [CBOR (Concise Binary Object Representation)](https://cbor.io). CBOR is een binary data format gebaseerd op JSON. Omdat de data als binary verstuurd wordt kost dit minder bytes en is het dus sneller als standaard JSON.
