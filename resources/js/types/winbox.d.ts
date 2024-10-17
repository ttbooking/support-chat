export interface WinBoxModel {
    id: string;
    title: string;
    index: number;
    x: number;
    y: number;
    width: number;
    height: number;
    min: boolean;
    max: boolean;
    full: boolean;
    hidden: boolean;
    focused: boolean;
}
