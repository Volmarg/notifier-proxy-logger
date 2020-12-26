/**
 * @description returns dto representation of backend dto which consist of Mail Entity data.
 */
export default class MailDto
{
    static readonly STATUS_SENT    = "SENT";
    static readonly STATUS_PENDING = "PENDING";
    static readonly STATUS_ERROR   = "ERROR";

    private _fromEmail: string        = "";
    private _subject:   string        = "";
    private _body:      string        = "";
    private _status:    string        = "";
    private _created:   string        = "";
    private _source:    string        = "";
    private _toEmails:  Array<string> = [];

    get fromEmail(): string {
        return this._fromEmail;
    }

    set fromEmail(value: string) {
        this._fromEmail = value;
    }

    get subject(): string {
        return this._subject;
    }

    set subject(value: string) {
        this._subject = value;
    }

    get body(): string {
        return this._body;
    }

    set body(value: string) {
        this._body = value;
    }

    get status(): string {
        return this._status;
    }

    set status(value: string) {
        this._status = value;
    }

    get created(): string {
        return this._created;
    }

    set created(value: string) {
        this._created = value;
    }

    get source(): string {
        return this._source;
    }

    set source(value: string) {
        this._source = value;
    }

    get toEmails(): any[] {
        return this._toEmails;
    }

    set toEmails(value: any[]) {
        this._toEmails = value;
    }

    /**
     * Will produce dto from json
     *
     * @param json
     */
    public static fromJson(json: string): MailDto
    {
        let object = JSON.parse(json);
        let dto    = new MailDto();

        dto.fromEmail = object.fromEmail;
        dto.subject   = object.subject;
        dto.body      = object.body;
        dto.status    = object.status;
        dto.created   = object.created;
        dto.source    = object.source;
        dto.toEmails  = object.toEmails;

        return dto;
    }

}