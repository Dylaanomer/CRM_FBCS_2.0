<?php
class Onderhoud
{
    // Properties
    private int $id;
    private string $Naam;
    private string $OSnaam;
    private string $Datum;
    private string $Medewerker;
    private bool $CCleaner;
    private bool $MBAM;
    private bool $KVRT;
    private bool $ADW;
    private bool $HITMANPRO;
    private bool $REGEDIT;
    private bool $Energiebeheer;
    private bool $WindowsActivated;
    private bool $Ongewenstprogrammas;
    private bool $UpdatesInstalleren;
    private bool $AvastInstellingen;
    private bool $AvastCode;
    private bool $FBCSSnelkoppeling;
    private bool $FBCSReminder;
    private bool $Internetbrowsers;
    private bool $SysteemBeveileging;
    private bool $AutomatischAfspelen;
    private bool $SchijfOpslag;
    private bool $SSDDiagnose;
    private bool $FBCSBureablad;
    private bool $PartitiesHardeSchijf;
    private bool $Windows26H1;
    private bool $LiveMailOffice;
    private bool $Wifi6;
    private bool $Gadgets;
    private bool $Games;
    private bool $Startallbackverwijderen;
    private bool $DownloadsVerwijderen;
    private bool $Meldingen;
    private bool $PrinterInstellingen;
    private bool $DeliveryOptimization;
    private bool $VeaamCONTROLE;
    private bool $SchijfOpruiming;

    // Getters
    public function getId(): int { return $this->id; }
    public function getNaam(): string { return $this->Naam; }
    public function getOSnaam(): string { return $this->OSnaam; }
    public function getDatum(): string { return $this->Datum; }
    public function getMedewerker(): string { return $this->Medewerker; }
    public function getCCleaner(): bool { return $this->CCleaner; }
    public function getMBAM(): bool { return $this->MBAM; }
    public function getKVRT(): bool { return $this->KVRT; }
    public function getADW(): bool { return $this->ADW; }
    public function getHITMANPRO(): bool { return $this->HITMANPRO; }
    public function getREGEDIT(): bool { return $this->REGEDIT; }
    public function getEnergiebeheer(): bool { return $this->Energiebeheer; }
    public function getWindowsActivated(): bool { return $this->WindowsActivated; }
    public function getOngewenstprogrammas(): bool { return $this->Ongewenstprogrammas; }
    public function getUpdatesInstalleren(): bool { return $this->UpdatesInstalleren; }
    public function getAvastInstellingen(): bool { return $this->AvastInstellingen; }
    public function getAvastCode(): bool { return $this->AvastCode; }
    public function getFBCSSnelkoppeling(): bool { return $this->FBCSSnelkoppeling; }
    public function getFBCSReminder(): bool { return $this->FBCSReminder; }
    public function getInternetbrowsers(): bool { return $this->Internetbrowsers; }
    public function getSysteemBeveileging(): bool { return $this->SysteemBeveileging; }
    public function getAutomatischAfspelen(): bool { return $this->AutomatischAfspelen; }
    public function getSchijfOpslag(): bool { return $this->SchijfOpslag; }
    public function getSSDDiagnose(): bool { return $this->SSDDiagnose; }
    public function getFBCSBureablad(): bool { return $this->FBCSBureablad; }
    public function getPartitiesHardeSchijf(): bool { return $this->PartitiesHardeSchijf; }
    public function getWindows26H1(): bool { return $this->Windows26H1; }
    public function getLiveMailOffice(): bool { return $this->LiveMailOffice; }
    public function getWifi6(): bool { return $this->Wifi6; }
    public function getGadgets(): bool { return $this->Gadgets; }
    public function getGames(): bool { return $this->Games; }
    public function getStartallbackverwijderen(): bool { return $this->Startallbackverwijderen; }
    public function getDownloadsVerwijderen(): bool { return $this->DownloadsVerwijderen; }
    public function getMeldingen(): bool { return $this->Meldingen; }
    public function getPrinterInstellingen(): bool { return $this->PrinterInstellingen; }
    public function getDeliveryOptimization(): bool { return $this->DeliveryOptimization; }
    public function getVeaamCONTROLE(): bool { return $this->VeaamCONTROLE; }
    public function getSchijfOpruiming(): bool { return $this->SchijfOpruiming; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setNaam(string $Naam): void { $this->Naam = $Naam; }
    public function setOSnaam(string $OSnaam): void { $this->OSnaam = $OSnaam; }
    public function setDatum(string $Datum): void { $this->Datum = $Datum; }
    public function setMedewerker(string $Medewerker): void { $this->Medewerker = $Medewerker; }
    public function setCCleaner(bool $CCleaner): void { $this->CCleaner = $CCleaner; }
    public function setMBAM(bool $MBAM): void { $this->MBAM = $MBAM; }
    public function setKVRT(bool $KVRT): void { $this->KVRT = $KVRT; }
    public function setADW(bool $ADW): void { $this->ADW = $ADW; }
    public function setHITMANPRO(bool $HITMANPRO): void { $this->HITMANPRO = $HITMANPRO; }
    public function setREGEDIT(bool $REGEDIT): void { $this->REGEDIT = $REGEDIT; }
    public function setEnergiebeheer(bool $Energiebeheer): void { $this->Energiebeheer = $Energiebeheer; }
    public function setWindowsActivated(bool $WindowsActivated): void { $this->WindowsActivated = $WindowsActivated; }
    public function setOngewenstprogrammas(bool $Ongewenstprogrammas): void { $this->Ongewenstprogrammas = $Ongewenstprogrammas; }
    public function setUpdatesInstalleren(bool $UpdatesInstalleren): void { $this->UpdatesInstalleren = $UpdatesInstalleren; }
    public function setAvastInstellingen(bool $AvastInstellingen): void { $this->AvastInstellingen = $AvastInstellingen; }
    public function setAvastCode(bool $AvastCode): void { $this->AvastCode = $AvastCode; }
    public function setFBCSSnelkoppeling(bool $FBCSSnelkoppeling): void { $this->FBCSSnelkoppeling = $FBCSSnelkoppeling; }
    public function setFBCSReminder(bool $FBCSReminder): void { $this->FBCSReminder = $FBCSReminder; }
    public function setInternetbrowsers(bool $Internetbrowsers): void { $this->Internetbrowsers = $Internetbrowsers; }
    public function setSysteemBeveileging(bool $SysteemBeveileging): void { $this->SysteemBeveileging = $SysteemBeveileging; }
    public function setAutomatischAfspelen(bool $AutomatischAfspelen): void { $this->AutomatischAfspelen = $AutomatischAfspelen; }
    public function setSchijfOpslag(bool $SchijfOpslag): void { $this->SchijfOpslag = $SchijfOpslag; }
    public function setSSDDiagnose(bool $SSDDiagnose): void { $this->SSDDiagnose = $SSDDiagnose; }
    public function setFBCSBureablad(bool $FBCSBureablad): void { $this->FBCSBureablad = $FBCSBureablad; }
    public function setPartitiesHardeSchijf(bool $PartitiesHardeSchijf): void { $this->PartitiesHardeSchijf = $PartitiesHardeSchijf; }
    public function setWindows26H1(bool $Windows26H1): void { $this->Windows26H1 = $Windows26H1; }
    public function setLiveMailOffice(bool $LiveMailOffice): void { $this->LiveMailOffice = $LiveMailOffice; }
    public function setWifi6(bool $Wifi6): void { $this->Wifi6 = $Wifi6; }
    public function setGadgets(bool $Gadgets): void { $this->Gadgets = $Gadgets; }
    public function setGames(bool $Games): void { $this->Games = $Games; }
    public function setStartallbackverwijderen(bool $Startallbackverwijderen): void { $this->Startallbackverwijderen = $Startallbackverwijderen; }
    public function setDownloadsVerwijderen(bool $DownloadsVerwijderen): void { $this->DownloadsVerwijderen = $DownloadsVerwijderen; }
    