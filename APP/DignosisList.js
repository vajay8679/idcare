/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 *
 * @format
 * @flow
 */

import React,{Component} from 'react';
import {
  SafeAreaView,
  StyleSheet,
  ScrollView,
  View,
  TextInput,
  StatusBar,
  TouchableOpacity,
  Image,
  FlatList,
  AsyncStorage,
  ActivityIndicator
 
} from 'react-native';
import Toast, {DURATION} from 'react-native-easy-toast'
import {Icon, Container, Header, Content, Footer, FooterTab, Button, Text,Item,Input,Form } from 'native-base';
import { SearchBar } from 'react-native-elements';
import PickerModal from 'react-native-picker-modal-view';
const list22 = [
	{Id: 1, Name: 'CHL', Value: 'Test1 Value'},
	{Id: 2, Name: 'Test2 Name', Value: 'Test2 Value'},
	{Id: 3, Name: 'Test3 Name', Value: 'Test3 Value'},
	{Id: 4, Name: 'Test4 Name', Value: 'Test4 Value'}
]
const DATA22 = [
  {
    id: 'bd7acbea-c1b1-46c2-aed5-3ad53abb28ba',
    title: 'First Item',
  },
  {
    id: '3ac68afc-c605-48d3-a4f8-fbd91aa97f63',
    title: 'Second Item',
  },
  {
    id: '58694a0f-3da1-471f-bd96-145571e29d72',
    title: 'Third Item',
  },
];
const CustomHeader = ({ navigation }) => (

    <View style={{ flexDirection: 'row' ,justifyContent:'center',backgroundColor:'blue'}}>
     <TouchableOpacity onPress={()=> navigation.goBack(null)} style={{flex:1,justifyContent:"flex-start"}} transparent>
     <Image
      source={require('./image/BackLight.png')}
    /> 
   
            </TouchableOpacity>
            
      <View style={{  justifyContent: 'center', paddingRight: 15 }}>
        {/* <Text>Conference</Text> */}
      </View>

    </View>

);
const DATA = [
  {
    id: 'bd7acbea-c1b1-46c2-aed5-3ad53abb28ba',
    title: 'First Item',
  },
  {
    id: '3ac68afc-c605-48d3-a4f8-fbd91aa97f63',
    title: 'Second Item',
  },
  {
    id: '58694a0f-3da1-471f-bd96-145571e29d72',
    title: 'Third Item',
  },
];
  export default class DignosisList extends Component {
   
    constructor(props) {
      super(props);
      this.state = {
        selectedItem: {},
        patientlistarray:[],
        alldataarr:[],
        login_role:"",
        new_initial_dx_name: '',
        new_initial_rx_name: '',
        animating: false,
      };
    }
  
    componentWillMount()
    {
      this.setState({
        animating: true
      })
      AsyncStorage.getItem('userdata', (err, result) => {
        const item = JSON.parse(result);
        const key = item.login_session_key;
        this.setState({
          login_role: item.login_role
        })
      
        let formdata = new FormData();
        formdata.append("login_session_key",key)

        if(item.login_role=='Md Steward')
        {
        formdata.append("md_steward_id",item.user_id)
        }
      
       
        if(this.props.navigation.getParam('care_unit_id'))
        {
        formdata.append("care_unit_id",this.props.navigation.getParam('care_unit_id'))
        }
      
         
        formdata.append("patient_id",this.props.navigation.getParam('patient_id'))
      
       console.log(formdata);
    
       fetch('http://idcaresteward.com/api/v1/user/patient_list_existing ',{
        method: 'post',
         headers: {
        'Content-Type': 'multipart/form-data',
         },
          body: formdata
         })
      .then((response) => response.json())
      .then((responseJson) => {
        console.log(responseJson);
      //  this.items = responseJson.response.map((item, key) =>
      //   this.setState({
      //   carelistdata: [...this.state.carelistdata, {'Id':item.id, 'Name':item.name,'Value':item.id}]
      //    })
         
      //    );

      if(responseJson.status==1 )
      {
      this.setState({patientlistarray:responseJson.response});
      this.setState({alldataarr:responseJson.response});
      this.setState({
        animating: false
      })
    }else{
      this.setState({
        animating: false
      })
      this.refs.toast.show(<View style={{ justifyContent:"center",alignItems:"center"}}>
      <Text style={{color:'white'}}>
     {responseJson.message}
    
      </Text>
      </View>); 
    }
     
      })
     .catch(err => {
   //  console.log(err)
     })  
    })
    }
    selected(selected) {
      this.setState({
        selectedItem: selected
      })
    }
    searchPatient() {
      this.props.navigation.navigate('Home');
     // alert(2323)
    // this.props.navigation.goBack();
    }
    goback() {
      // this.props.navigation.navigate('Home');
      // alert(2323)
      this.props.navigation.goBack();
     }

    //  searchFilterFunction = text => {    
    //   const newData = this.state.patientlistarray.filter(item => {      
    //     const itemData = `${item.patient_id}   
    //     ${item.name.first.toUpperCase()} ${item.name.last.toUpperCase()}`;
        
    //      const textData = text.toUpperCase();
          
    //      return itemData.indexOf(textData) > -1;    
    //   });
      
    //   this.setState({ data: newData });  
    // };

    searchFilterFunction(search) {
      console.log(search)    
      this.setState({ search: search })
   //let arrAllData =  this.state.patientlistarray;
      let text = search.toLowerCase()
     if(this.state.alldataarr)
     {
        console.log('item') 
      let filteredName = this.state.alldataarr.filter((item) => {
        console.log(item)   
        return item.patient_id.toLowerCase().match(text)
      })
  
  
      if (filteredName.length == 0) {
        // set no data flag to true so as to render flatlist conditionally

        console.log(filteredName)
        this.setState({
          noData: true,
          patientlistarray: filteredName
        })
      } else if (Array.isArray(filteredName)) {
        this.setState({
          noData: false,
          patientlistarray: filteredName
        })
      }
    }
    //  alert(this.state.users)
    }
    
    EditPentry(id) {
     
      this.props.navigation.navigate('EditPentry', {
        patient_id: id,
     
      });
     // alert(2323)
    }
    render() {
let data=''
//console.log(this.state.patientlistarray.length)
   if(this.state.patientlistarray )
   {

    data=  <FlatList
        data={this.state.patientlistarray}
        renderItem={({ item }) => <View style={{ padding:20, width:'100%', flexDirection:'row',justifyContent:'center',alignItems:'center'}}>
        <View style={{ width:'30%'}}>
    <Text style={{textAlign:'left',color:'#747474',fontSize:14}} >{item.date_of_start_abx}</Text>
       </View>
        <View style={{width:'50%'}}>
        <Text style={{textAlign:'left',color:'#747474',fontSize:14}} >{item.initial_dx_name}</Text>
        </View>
        {/* { item.new_initial_dx_name==null&& item.new_initial_rx_name==null  ? */}
        { item.md_patient_status=='Pending'  ?
        
        <TouchableOpacity   onPress={this.EditPentry.bind(this,item.pid)} style={{ width:'20%'}} >
        <Text  style={{textAlign:'center',color:'#484B79',textDecorationLine:'underline',fontSize:14}}>View</Text>
        </TouchableOpacity>
        :
        <TouchableOpacity   onPress={this.EditPentry.bind(this,item.pid)} style={{ width:'20%'}} >
        <Text  style={{textAlign:'center',color:'#484B79',textDecorationLine:'underline',fontSize:14}}>Reviewed</Text>
        </TouchableOpacity>
        }
      </View>}
        keyExtractor={item => item.id}
      />
   }
   else{
    data= <View>
    <Text style={{textAlign:'center'}}>No Record Found.</Text>
    </View>

   }
   
     
    
      
  return (
    <SafeAreaView style={{backgroundColor:'#B22B57',flex:1}} >
    
   <StatusBar barStyle='light-content' backgroundColor='#B22B57' ></StatusBar>  
       
       <Container style={{backgroundColor:'white'}}> 
    {/* <Header /> */}
    <Toast ref="toast"  position='center' fadeOutDuration={1000}/>
    <View style={{ paddingTop:15, flexDirection:'row',justifyContent:'space-around',backgroundColor:"#B22B57",height:100,borderBottomLeftRadius:20,borderBottomRightRadius:20}} >
    <TouchableOpacity  onPress={this.goback.bind(this)}  style={{height:50, width:'30%',alignItems:'flex-start'}}>
   
    <Image style={{width:15,height:15,tintColor:'white',marginLeft:10}}  source={require('./image/back.png')} ></Image>
       </TouchableOpacity>
       <View style={{ width:'30%',alignItems:'center'}}>
       <Text style={{fontWeight:'bold',color:'white',fontSize:15}}>Patient History</Text>
       </View>
       <TouchableOpacity  onPress={this.searchPatient.bind(this)}  style={{ width:'30%',alignItems:'flex-end'}}>
      
       <Image style={{marginRight:15, width:15,height:15,tintColor:'white'}}  source={require('./image/home.png')} ></Image>
     </TouchableOpacity>
    
       </View> 
      
       <View style={{paddingLeft:5, height:37, opacity: 5,flexDirection:'row', borderWidth:0.5,borderColor:'#F7F7F7', backgroundColor:'white', width:'90%',alignItems:'center',alignSelf:'center',position: 'absolute',marginTop:70,zIndex:1,borderRadius:7}}> 
  <Text style={{textAlign:'center'}}>Patient ID : {this.props.navigation.getParam('patient_id')}</Text>
          {/* <Input 

          
        onChangeText={text => this.searchFilterFunction(text)}
    //  onSubmitEditing={() => this.password.focus()}
       placeholder='Enter ID' 
       placeholderTextColor='#747474'
       fontSize={15}
       style={{paddingLeft:10}}
       keyboardType={'number-pad'}
       
       /> */}
      
      {/* <View style={{  borderTopRightRadius:10,borderBottomRightRadius:10}}>
          <Icon  style={{ fontSize:22, color:'#858585',height:37,paddingTop:13,paddingRight:10}} active name='search' />
          </View> */}
          
           
      </View>
    
     {/* <View style={{flexDirection:'row',justifyContent:'space-around',backgroundColor:'#f7f7f7',marginTop:25, height:40,alignItems:'center'}}>
       <Text>ID</Text>
       <Text>Diagnotics</Text>
       <Text>Action</Text>
     </View> */}
     
        <View style={{padding:20, backgroundColor:'#f7f7f7',width:'100%',marginTop:25,padding:20, marginBottom:20, flexDirection:'row',alignItems:'center',alignSelf:'center'}}>
       <View style={{ width:'30%',paddingLeft:5}}>
        <Text style={{textAlign:'left',fontWeight:'900',color:'#747474',fontSize:15}} >Date of Abx</Text>
      </View>
       <View style={{width:'50%' ,paddingLeft:5}}>
       <Text style={{textAlign:'left',fontWeight:'900',color:'#747474',fontSize:15}} >Diagnosis</Text>
       </View>
       <View style={{width:'20%',paddingLeft:5}}>
       <Text style={{textAlign:'center',fontWeight:'900',color:'#747474',fontSize:15}}>Action</Text>
       </View>
     </View>
     <View style={{ flex: 1}}>
     {this.state.animating==true &&
            <ActivityIndicator
               animating = {this.state.animating}
               color = '#B22B57'
               size = "large"
               style = {styles.activityIndicator}/>
     }

   
  {data}
 
      </View>


     {/* <View style={{ width:'100%', flexDirection:'row',justifyContent:'center',height:40,alignItems:'center'}}>
       <View style={{ width:'30%'}}>
        <Text style={{textAlign:'left'}} >45969696</Text>
      </View>
       <View style={{width:'30%'}}>
       <Text style={{textAlign:'center'}} >David Menkt</Text>
       </View>
       <TouchableOpacity style={{width:'30%'}}>
       <Text style={{textAlign:'center',color:'#484B79',textDecorationLine:'underline'}}>View</Text>
       </TouchableOpacity>
     </View>

     <View style={{ width:'100%', flexDirection:'row',justifyContent:'center',height:40,alignItems:'center'}}>
       <View style={{ width:'30%'}}>
        <Text style={{textAlign:'left'}} >234</Text>
      </View>
       <View style={{width:'30%'}}>
       <Text style={{textAlign:'center'}} >David Menkt</Text>
       </View>
       <TouchableOpacity style={{width:'30%'}}>
       <Text style={{textAlign:'center',color:'#484B79',textDecorationLine:'underline'}}>View</Text>
       </TouchableOpacity>
     </View>

     <View style={{ width:'100%', flexDirection:'row',justifyContent:'center',height:40,alignItems:'center'}}>
       <View style={{ width:'30%'}}>
        <Text style={{textAlign:'left'}} >567</Text>
      </View>
       <View style={{width:'30%'}}>
       <Text style={{textAlign:'center'}} >David Menkt</Text>
       </View>
       <TouchableOpacity style={{width:'30%'}}>
       <Text style={{textAlign:'center',color:'#484B79',textDecorationLine:'underline'}}>View</Text>
       </TouchableOpacity>
     </View>
     <View style={{ width:'100%', flexDirection:'row',justifyContent:'center',height:40,alignItems:'center'}}>
       <View style={{ width:'30%'}}>
        <Text style={{textAlign:'left'}} >567</Text>
      </View>
       <View style={{width:'30%'}}>
       <Text style={{textAlign:'center'}} >David Menkt </Text>
       </View>
       <TouchableOpacity style={{width:'30%'}}>
       <Text style={{textAlign:'center',color:'#484B79',textDecorationLine:'underline'}}>View</Text>
       </TouchableOpacity>
     </View> */}
   

  </Container>

  </SafeAreaView>
  );
}
};


const styles = StyleSheet.create({

  activityIndicator: {
   //  flex: 1,
   position: 'absolute',
   left: 0,
   right: 0,
   top: 0,
   bottom: 0,
   alignItems: 'center',
   justifyContent: 'center',
   //backgroundColor:'#F5FCFF',
 //  backgroundColor: 'rgba(40, 40, 40, 0.5)',
   zIndex:9999
  }
 
});


